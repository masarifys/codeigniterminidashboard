#!/bin/bash

# Duitku Payment Gateway Setup Script
# This script helps set up the Duitku payment gateway integration

echo "================================================"
echo "Duitku Payment Gateway Setup"
echo "================================================"
echo ""

# Check if we're in the right directory
if [ ! -f "spark" ]; then
    echo "Error: This script must be run from the CodeIgniter project root directory"
    exit 1
fi

# Check PHP version
echo "Checking PHP version..."
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "PHP Version: $PHP_VERSION"
echo ""

# Run migrations
echo "Running database migrations..."
php spark migrate
if [ $? -eq 0 ]; then
    echo "✓ Migrations completed successfully"
else
    echo "✗ Migration failed. Please check your database configuration."
    exit 1
fi
echo ""

# Seed test data
echo "Seeding test data..."
php spark db:seed TestDataSeeder
if [ $? -eq 0 ]; then
    echo "✓ Test data seeded successfully"
else
    echo "✗ Seeding failed"
fi
echo ""

# Seed invoice items
echo "Seeding invoice items and transactions..."
php spark db:seed InvoiceItemsSeeder
if [ $? -eq 0 ]; then
    echo "✓ Invoice items seeded successfully"
else
    echo "✗ Invoice items seeding failed"
fi
echo ""

echo "================================================"
echo "Setup Complete!"
echo "================================================"
echo ""
echo "Next steps:"
echo "1. Start the development server:"
echo "   php spark serve"
echo ""
echo "2. Access the application at:"
echo "   http://localhost:8080"
echo ""
echo "3. Login with test credentials:"
echo "   Username: testclient"
echo "   Password: password123"
echo ""
echo "4. Navigate to Invoices to test the payment gateway"
echo ""
echo "Duitku Sandbox Credentials:"
echo "   Merchant Code: DS16902"
echo "   API Key: 792f56c9e2277927191c4c4924f06b40"
echo ""
echo "For more information, see DUITKU_IMPLEMENTATION_GUIDE.md"
echo "================================================"
