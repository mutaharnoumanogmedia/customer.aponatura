@extends('layouts.customer.app')
@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Meine Wunschliste</h2>

        <div class="row g-4">
            <!-- Wishlist Item Start -->
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <img src="https://baaboo.com/cdn/shop/files/1er_GreenFoxx-Zireonensaeure_520x.jpg?v=1709208126"
                        class="card-img-top" alt="Produktbild">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Produktname A</h5>
                        <p class="card-text text-muted mb-2">Kategorie: Pflege</p>
                        <p class="card-text fw-bold mb-3">€39,99</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="bi bi-cart-plus me-1"></i> In den Warenkorb
                            </a>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Wishlist Item End -->

            <!-- Repeat wishlist items -->
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <img src="https://baaboo.com/cdn/shop/files/Green_Nutrition_Leber_Komplex_Kapseln_baaboo_520x.jpg?v=1746612642"
                        class="card-img-top" alt="Produktbild">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Produktname B</h5>
                        <p class="card-text text-muted mb-2">Kategorie: Duft</p>
                        <p class="card-text fw-bold mb-3">€59,00</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="bi bi-cart-plus me-1"></i> In den Warenkorb
                            </a>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Repeat -->
        </div>

        <div class="text-end mt-4">
            <a href="#" class="btn btn-outline-secondary">
                <i class="bi bi-box-arrow-left me-1"></i> Zurück zum Shop
            </a>
        </div>
    </div>
@endsection
