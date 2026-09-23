<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use OwenIt\Auditing\Models\Audit;
use Tests\TestCase;

class AuditingAdvancedFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        config(['audit.console' => true]);

        $this->user = User::factory()->create();
    }

    /**
     * Test 1-Click Rollback for updated product model.
     */
    public function test_audit_rollback_updated_product(): void
    {
        $product = Product::create([
            'name' => 'Original Item',
            'price' => 1200,
            'status' => 'active',
        ]);

        $product->update([
            'name' => 'Original Item',
            'price' => 2500,
            'status' => 'inactive',
        ]);

        $audit = Audit::where('event', 'updated')->latest()->first();
        $this->assertNotNull($audit);

        $response = $this->actingAs($this->user)
            ->post(route('audit.rollback', $audit->id));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => 1200,
            'status' => 'active',
        ]);
    }

    /**
     * Test 1-Click Rollback for deleted product (restoring deleted model).
     */
    public function test_audit_rollback_deleted_product(): void
    {
        $product = Product::create([
            'name' => 'Deleted Gadget',
            'price' => 4500,
            'status' => 'active',
        ]);

        $productId = $product->id;
        $product->delete();

        $audit = Audit::where('event', 'deleted')->latest()->first();
        $this->assertNotNull($audit);

        $response = $this->actingAs($this->user)
            ->post(route('audit.rollback', $audit->id));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', [
            'name' => 'Deleted Gadget',
            'price' => 4500,
        ]);
    }

    /**
     * Test Suspicious Audit Threat & Security Anomaly Detector on Dashboard.
     */
    public function test_suspicious_audit_threat_detector_dashboard(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $p = Product::create(['name' => "Item {$i}", 'price' => 100, 'status' => 'active']);
            $p->delete();
        }

        $response = $this->actingAs($this->user)
            ->get(route('audit.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Suspicious Audit Threat');
        $response->assertSee('CRITICAL THREAT DETECTED');
    }

    /**
     * Test Deep Side-by-Side Visual Diff Inspector rendering.
     */
    public function test_deep_side_by_side_visual_diff_inspector(): void
    {
        $product = Product::create([
            'name' => 'Initial Title',
            'price' => 500,
            'status' => 'active',
        ]);

        $product->update([
            'price' => 900,
        ]);

        $audit = Audit::where('event', 'updated')->latest()->first();

        $response = $this->actingAs($this->user)
            ->get(route('audit.show', $audit->id));

        $response->assertStatus(200);
        $response->assertSee('Deep Side-by-Side Visual Diff Inspector');
        $response->assertSee('MODIFIED');
    }
}
