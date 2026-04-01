#!/bin/bash

echo "Starting installation..."

BASE="/c/xampp/htdocs/Kaknani/karupuak-arsyla"

# ========================
# BASE
# ========================
mkdir -p "$BASE"

# ========================
# ROOT FILES
# ========================
touch "$BASE/index.php"
touch "$BASE/.htaccess"
touch "$BASE/README.md"

# ========================
# CONFIG
# ========================
mkdir -p "$BASE/config"
touch "$BASE/config/app.php"
touch "$BASE/config/database.php"
touch "$BASE/config/midtrans.php"

# ========================
# CORE
# ========================
mkdir -p "$BASE/core"
touch "$BASE/core/Application.php"
touch "$BASE/core/Router.php"
touch "$BASE/core/Request.php"
touch "$BASE/core/Response.php"
touch "$BASE/core/Session.php"
touch "$BASE/core/Validator.php"
touch "$BASE/core/Middleware.php"

# ========================
# DOMAIN
# ========================

# PRODUCT
mkdir -p "$BASE/src/Domain/Product/Entities"
mkdir -p "$BASE/src/Domain/Product/ValueObjects"
mkdir -p "$BASE/src/Domain/Product/Repositories"
touch "$BASE/src/Domain/Product/Entities/Product.php"
touch "$BASE/src/Domain/Product/ValueObjects/ProductId.php"
touch "$BASE/src/Domain/Product/ValueObjects/Price.php"
touch "$BASE/src/Domain/Product/Repositories/ProductRepositoryInterface.php"

# ORDER
mkdir -p "$BASE/src/Domain/Order/Entities"
mkdir -p "$BASE/src/Domain/Order/ValueObjects"
mkdir -p "$BASE/src/Domain/Order/Repositories"
touch "$BASE/src/Domain/Order/Entities/Order.php"
touch "$BASE/src/Domain/Order/Entities/OrderItem.php"
touch "$BASE/src/Domain/Order/ValueObjects/OrderId.php"
touch "$BASE/src/Domain/Order/ValueObjects/OrderStatus.php"
touch "$BASE/src/Domain/Order/Repositories/OrderRepositoryInterface.php"

# USER
mkdir -p "$BASE/src/Domain/User/Entities"
mkdir -p "$BASE/src/Domain/User/ValueObjects"
mkdir -p "$BASE/src/Domain/User/Repositories"
touch "$BASE/src/Domain/User/Entities/User.php"
touch "$BASE/src/Domain/User/ValueObjects/UserId.php"
touch "$BASE/src/Domain/User/ValueObjects/Email.php"
touch "$BASE/src/Domain/User/ValueObjects/Role.php"
touch "$BASE/src/Domain/User/Repositories/UserRepositoryInterface.php"

# CART
mkdir -p "$BASE/src/Domain/Cart/Entities"
mkdir -p "$BASE/src/Domain/Cart/Repositories"
touch "$BASE/src/Domain/Cart/Entities/Cart.php"
touch "$BASE/src/Domain/Cart/Entities/CartItem.php"
touch "$BASE/src/Domain/Cart/Repositories/CartRepositoryInterface.php"

# REVIEW
mkdir -p "$BASE/src/Domain/Review/Entities"
mkdir -p "$BASE/src/Domain/Review/Repositories"
touch "$BASE/src/Domain/Review/Entities/Review.php"
touch "$BASE/src/Domain/Review/Repositories/ReviewRepositoryInterface.php"

# CATEGORY
mkdir -p "$BASE/src/Domain/Category/Entities"
mkdir -p "$BASE/src/Domain/Category/Repositories"
touch "$BASE/src/Domain/Category/Entities/Category.php"
touch "$BASE/src/Domain/Category/Repositories/CategoryRepositoryInterface.php"

# ========================
# APPLICATION (CQRS)
# ========================
mkdir -p "$BASE/src/Application/Commands"
mkdir -p "$BASE/src/Application/Queries"

touch "$BASE/src/Application/Commands/CommandBus.php"
touch "$BASE/src/Application/Commands/CommandHandlerInterface.php"
touch "$BASE/src/Application/Queries/QueryBus.php"
touch "$BASE/src/Application/Queries/QueryHandlerInterface.php"

# AUTH
mkdir -p "$BASE/src/Application/Commands/Auth"
touch "$BASE/src/Application/Commands/Auth/RegisterUserCommand.php"
touch "$BASE/src/Application/Commands/Auth/RegisterUserHandler.php"
touch "$BASE/src/Application/Commands/Auth/LoginCommand.php"
touch "$BASE/src/Application/Commands/Auth/LoginHandler.php"

# PRODUCT COMMAND
mkdir -p "$BASE/src/Application/Commands/Product"
touch "$BASE/src/Application/Commands/Product/CreateProductCommand.php"
touch "$BASE/src/Application/Commands/Product/CreateProductHandler.php"
touch "$BASE/src/Application/Commands/Product/UpdateProductCommand.php"
touch "$BASE/src/Application/Commands/Product/UpdateProductHandler.php"
touch "$BASE/src/Application/Commands/Product/DeleteProductCommand.php"
touch "$BASE/src/Application/Commands/Product/DeleteProductHandler.php"

# ========================
# INFRASTRUCTURE
# ========================
mkdir -p "$BASE/src/Infrastructure/Persistence"
mkdir -p "$BASE/src/Infrastructure/Payment"
mkdir -p "$BASE/src/Infrastructure/Shipping"

touch "$BASE/src/Infrastructure/Persistence/MySQLProductRepository.php"
touch "$BASE/src/Infrastructure/Persistence/MySQLOrderRepository.php"
touch "$BASE/src/Infrastructure/Persistence/MySQLUserRepository.php"
touch "$BASE/src/Infrastructure/Persistence/MySQLCartRepository.php"
touch "$BASE/src/Infrastructure/Persistence/MySQLReviewRepository.php"
touch "$BASE/src/Infrastructure/Persistence/MySQLCategoryRepository.php"

touch "$BASE/src/Infrastructure/Payment/MidtransService.php"
touch "$BASE/src/Infrastructure/Shipping/RajaOngkirService.php"

# ========================
# CONTROLLERS
# ========================
mkdir -p "$BASE/app/Controllers"
mkdir -p "$BASE/app/Controllers/Admin"

touch "$BASE/app/Controllers/HomeController.php"
touch "$BASE/app/Controllers/AuthController.php"
touch "$BASE/app/Controllers/CartController.php"
touch "$BASE/app/Controllers/OrderController.php"
touch "$BASE/app/Controllers/PaymentController.php"
touch "$BASE/app/Controllers/ReviewController.php"

touch "$BASE/app/Controllers/Admin/AdminDashboardController.php"
touch "$BASE/app/Controllers/Admin/AdminUserController.php"
touch "$BASE/app/Controllers/Admin/AdminProductController.php"
touch "$BASE/app/Controllers/Admin/AdminCategoryController.php"
touch "$BASE/app/Controllers/Admin/AdminOrderController.php"
touch "$BASE/app/Controllers/Admin/AdminPaymentController.php"
touch "$BASE/app/Controllers/Admin/AdminReviewController.php"
touch "$BASE/app/Controllers/Admin/AdminReportController.php"
touch "$BASE/app/Controllers/Admin/AdminShippingController.php"

# ========================
# VIEWS
# ========================
mkdir -p "$BASE/app/Views/layouts"
mkdir -p "$BASE/app/Views/home"
mkdir -p "$BASE/app/Views/auth"
mkdir -p "$BASE/app/Views/cart"
mkdir -p "$BASE/app/Views/checkout"
mkdir -p "$BASE/app/Views/order"
mkdir -p "$BASE/app/Views/review"
mkdir -p "$BASE/app/Views/product"

mkdir -p "$BASE/app/Views/admin/users"
mkdir -p "$BASE/app/Views/admin/products"
mkdir -p "$BASE/app/Views/admin/categories"
mkdir -p "$BASE/app/Views/admin/orders"
mkdir -p "$BASE/app/Views/admin/payments"
mkdir -p "$BASE/app/Views/admin/reviews"
mkdir -p "$BASE/app/Views/admin/shipping"
mkdir -p "$BASE/app/Views/admin/reports"

# ========================
# DATABASE
# ========================
mkdir -p "$BASE/database/migrations"
mkdir -p "$BASE/database/seeders"
touch "$BASE/database/migrations/create_tables.sql"
touch "$BASE/database/seeders/seed.sql"

# ========================
# PUBLIC
# ========================
mkdir -p "$BASE/public/css"
mkdir -p "$BASE/public/js"
mkdir -p "$BASE/public/uploads/products"

touch "$BASE/public/css/custom.css"
touch "$BASE/public/js/app.js"
touch "$BASE/public/js/cart.js"
touch "$BASE/public/js/payment.js"

# ========================
# STORAGE
# ========================
mkdir -p "$BASE/storage/logs"
touch "$BASE/storage/logs/app.log"

echo "====================================="
echo "INSTALLATION SUCCESS ✅"
echo "Path: $BASE"
echo "====================================="

# preview
find "$BASE" -type d | head -50