<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadPaymentProofTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upload_payment_proof_on_success_page(): void
    {
        Storage::fake('public');

        // Create an order
        $order = Order::create([
            'customer_name'    => 'John Doe',
            'customer_email'   => 'john@example.com',
            'customer_phone'   => '012345678',
            'customer_address' => 'Phnom Penh',
            'total_amount'     => 15.50,
            'status'           => 'pending',
            'payment_proof'    => null,
            'payment_verified' => false,
        ]);

        // Visit success page and verify it shows the upload form
        $response = $this->get(route('checkout.success', $order->id));
        $response->assertStatus(200);
        $response->assertSee(__('No Payment Screenshot'));
        $response->assertSee(__('Upload Screenshot'));

        // Post a fake image to upload-proof route
        $file = UploadedFile::fake()->createWithContent('payment_screenshot.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));
        
        $uploadResponse = $this->post(route('checkout.uploadProof', $order->id), [
            'payment_proof' => $file,
        ]);

        // Assert it redirects back
        $uploadResponse->assertRedirect();

        // Refresh model from DB
        $order->refresh();

        // Assert file exists in storage and database contains path
        $this->assertNotNull($order->payment_proof);
        Storage::disk('public')->assertExists($order->payment_proof);

        // Assert payment is not verified yet
        $this->assertFalse($order->payment_verified);
    }

    public function test_uploading_invalid_file_fails(): void
    {
        Storage::fake('public');

        $order = Order::create([
            'customer_name'    => 'John Doe',
            'customer_email'   => 'john@example.com',
            'customer_phone'   => '012345678',
            'customer_address' => 'Phnom Penh',
            'total_amount'     => 15.50,
            'status'           => 'pending',
            'payment_proof'    => null,
            'payment_verified' => false,
        ]);

        // Post a text file instead of image
        $file = UploadedFile::fake()->create('not_an_image.txt', 100);

        $uploadResponse = $this->post(route('checkout.uploadProof', $order->id), [
            'payment_proof' => $file,
        ]);

        $uploadResponse->assertSessionHasErrors(['payment_proof']);
        
        $order->refresh();
        $this->assertNull($order->payment_proof);
    }

    public function test_user_can_get_order_status_polling(): void
    {
        $order = Order::create([
            'customer_name'    => 'John Doe',
            'customer_email'   => 'john@example.com',
            'customer_phone'   => '012345678',
            'customer_address' => 'Phnom Penh',
            'total_amount'     => 15.50,
            'status'           => 'preparing',
            'payment_proof'    => 'proofs/fake.png',
            'payment_verified' => true,
            'estimated_delivery_minutes' => 30,
        ]);

        $response = $this->get(route('orders.getStatus', $order->id));
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'preparing',
            'payment_verified' => true,
            'estimated_delivery_minutes' => 30,
        ]);
        
        $this->assertArrayHasKey('remaining_seconds', $response->json());
    }

    public function test_admin_can_update_delivery_minutes_duration(): void
    {
        // Create an admin user
        $admin = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $order = Order::create([
            'customer_name'    => 'John Doe',
            'customer_email'   => 'john@example.com',
            'customer_phone'   => '012345678',
            'customer_address' => 'Phnom Penh',
            'total_amount'     => 15.50,
            'status'           => 'pending',
        ]);

        // Attempt without logging in - should redirect to admin login page
        $response = $this->patch(route('admin.orders.updateDeliveryMinutes', $order->id), [
            'delivery_minutes' => 45,
        ]);
        $response->assertRedirect(route('admin.login'));

        // Log in as admin and try again
        $response = $this->actingAs($admin)->patch(route('admin.orders.updateDeliveryMinutes', $order->id), [
            'delivery_minutes' => 45,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'estimated_delivery_minutes' => 45,
            'status' => 'confirmed',
        ]);

        $order->refresh();
        $this->assertEquals(45, $order->estimated_delivery_minutes);
        $this->assertEquals('confirmed', $order->status);
    }

    public function test_admin_can_delete_order(): void
    {
        $admin = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $order = Order::create([
            'customer_name'    => 'John Doe',
            'customer_email'   => 'john@example.com',
            'customer_phone'   => '012345678',
            'customer_address' => 'Phnom Penh',
            'total_amount'     => 15.50,
            'status'           => 'pending',
        ]);

        $response = $this->delete(route('admin.orders.destroy', $order->id));
        $response->assertRedirect(route('admin.login'));

        $response = $this->actingAs($admin)->delete(route('admin.orders.destroy', $order->id));
        $response->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseMissing('orders', [
            'id' => $order->id,
        ]);
    }

    public function test_checkout_calculates_dynamic_estimated_delivery_time(): void
    {
        // 1. Create test products
        $product1 = \App\Models\Product::create([
            'name' => 'Fast Dish',
            'price' => 5.00,
            'stock' => 10,
            'category' => 'alacarte',
            'prep_time_minutes' => 10,
        ]);

        $product2 = \App\Models\Product::create([
            'name' => 'Slow Dish',
            'price' => 10.00,
            'stock' => 5,
            'category' => 'alacarte',
            'prep_time_minutes' => 25,
        ]);

        // 2. Mock session cart with both products
        $cart = [
            'key1' => [
                'product_id' => $product1->id,
                'quantity' => 1,
                'options' => [],
            ],
            'key2' => [
                'product_id' => $product2->id,
                'quantity' => 1,
                'options' => [],
            ],
        ];
        
        session(['cart' => $cart]);

        // 3. Post to store checkout route
        $response = $this->post(route('checkout.store'), [
            'customer_name' => 'Jane Doe',
            'customer_email' => 'jane@example.com',
            'customer_phone' => '0987654321',
            'customer_address' => 'Siem Reap',
        ]);

        // 4. Assert response redirects
        $response->assertRedirect();
        
        // 5. Assert the created order has dynamic estimated delivery minutes equal to max prep time (25) + 15 buffer = 40 minutes
        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals(40, $order->estimated_delivery_minutes);
    }
}
