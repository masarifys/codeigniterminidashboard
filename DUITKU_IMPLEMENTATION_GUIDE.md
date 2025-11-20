# Duitku Payment Gateway Implementation Guide

## Overview
This guide documents the implementation of Duitku payment gateway integration with invoice detail view and payment status detection.

## Features Implemented

### 1. Invoice Detail Page
- Display invoice number with status badge (PAID/UNPAID)
- Show invoiced to and pay to information
- Display invoice date and payment method
- List invoice items with subtotal and total
- Show transaction details for paid invoices
- Action buttons (Pay Now for unpaid, Download PDF for paid, Back to list)

### 2. Payment Gateway Integration
- Duitku Sandbox Mode integration
- Create payment request API
- Payment callback handling
- Transaction status tracking
- Automatic invoice status update

### 3. Invoice List Enhancement
- Pay Now button for unpaid invoices
- View button for all invoices
- Proper routing and navigation

## Files Created/Modified

### New Files Created:

1. **app/Config/Duitku.php**
   - Duitku configuration with sandbox credentials
   - Merchant Code: DS16902
   - API Key: 792f56c9e2277927191c4c4924f06b40
   - Sandbox mode enabled by default

2. **app/Libraries/DuitkuPayment.php**
   - Payment gateway library with methods:
     - `createInvoice()` - Create payment request
     - `getPaymentMethods()` - Get available payment methods
     - `checkTransactionStatus()` - Check payment status
     - `validateCallback()` - Validate callback signature
     - `generateSignature()` - Generate API signature

3. **app/Models/InvoiceItemModel.php**
   - Model for invoice_items table
   - Manages invoice line items

4. **app/Models/TransactionModel.php**
   - Model for transactions table
   - Manages payment transaction records

5. **app/Database/Migrations/2025-11-20-181000_CreateInvoiceItemsTable.php**
   - Migration for invoice_items table
   - Columns: id, invoice_id, description, amount, timestamps

6. **app/Database/Migrations/2025-11-20-181001_CreateTransactionsTable.php**
   - Migration for transactions table
   - Columns: id, invoice_id, user_id, transaction_id, gateway, amount, transaction_date, status, timestamps

7. **app/Views/client/invoice_detail.php**
   - Invoice detail view with all required components
   - Responsive design matching existing layout
   - Conditional display based on payment status

8. **app/Database/Seeds/InvoiceItemsSeeder.php**
   - Seeder for sample invoice items
   - Creates transaction record for paid invoice

### Modified Files:

1. **app/Controllers/Client.php**
   - Added `invoiceDetail($id)` method
   - Added `payInvoice($id)` method
   - Added `paymentCallback()` method
   - Added necessary model imports

2. **app/Config/Routes.php**
   - Added route: `client/invoice/(:num)` -> `Client::invoiceDetail/$1`
   - Added route: `client/invoice/(:num)/pay` -> `Client::payInvoice/$1`
   - Added route: `client/payment/callback` -> `Client::paymentCallback`

3. **app/Views/client/invoices.php**
   - Updated action buttons to use proper links
   - Pay Now button links to payment page
   - View button links to invoice detail page

4. **public/assets/css/client-style.css**
   - Added styles for invoice detail page
   - Added badge styling for PAID/UNPAID status
   - Added responsive styles

## Database Schema

### invoice_items Table
```sql
CREATE TABLE invoice_items (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT(11) UNSIGNED NOT NULL,
    description TEXT NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
);
```

### transactions Table
```sql
CREATE TABLE transactions (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT(11) UNSIGNED NOT NULL,
    user_id INT(11) UNSIGNED NOT NULL,
    transaction_id VARCHAR(255) NOT NULL,
    gateway VARCHAR(100) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    transaction_date DATETIME NOT NULL,
    status ENUM('pending', 'success', 'failed') DEFAULT 'pending',
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Setup Instructions

### 1. Run Database Migrations
```bash
php spark migrate
```

### 2. Seed Test Data
```bash
# Seed basic data (users, services, invoices, tickets)
php spark db:seed TestDataSeeder

# Seed invoice items and transactions
php spark db:seed InvoiceItemsSeeder
```

### 3. Start Development Server
```bash
php spark serve
```

### 4. Access the Application
```
URL: http://localhost:8080
Username: testclient
Password: password123
```

## Payment Flow

### User Flow:
1. User logs into client portal
2. User navigates to Invoices page
3. User clicks "Pay Now" button on unpaid invoice
4. System creates payment request to Duitku
5. User is redirected to Duitku payment page
6. User completes payment on Duitku
7. Duitku sends callback to system
8. System validates callback signature
9. System updates invoice status to "paid"
10. System saves transaction details
11. User is redirected back to invoice detail page

### Technical Flow:
```
Client::payInvoice()
    ↓
DuitkuPayment::createInvoice()
    ↓
Duitku API (Sandbox)
    ↓
User Payment Page
    ↓
Duitku Callback
    ↓
Client::paymentCallback()
    ↓
DuitkuPayment::validateCallback()
    ↓
Update Invoice Status
    ↓
Save Transaction Record
```

## API Endpoints Used

### 1. Create Payment Request
- **Endpoint:** `POST https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry`
- **Purpose:** Create payment invoice
- **Response:** Payment URL for redirect

### 2. Payment Methods
- **Endpoint:** `POST https://sandbox.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod`
- **Purpose:** Get available payment methods
- **Response:** List of payment methods with fees

### 3. Transaction Status
- **Endpoint:** `POST https://sandbox.duitku.com/webapi/api/merchant/transactionStatus`
- **Purpose:** Check payment status
- **Response:** Transaction status and details

### 4. Callback (Webhook)
- **Endpoint:** `POST {your_domain}/client/payment/callback`
- **Purpose:** Receive payment notification from Duitku
- **Must validate:** Signature verification required

## Security Considerations

### 1. Signature Validation
- All callbacks from Duitku must validate signature
- Signature formula: `md5(merchantCode + amount + merchantOrderId + apiKey)`
- Invalid signatures are rejected and logged

### 2. Invoice Ownership Verification
- Always verify invoice belongs to logged-in user
- Check `invoice['user_id']` matches `session()->get('id')`
- Return 404 or redirect if ownership check fails

### 3. Payment Status Verification
- Check invoice isn't already paid before processing payment
- Prevent duplicate payments for same invoice
- Log all payment attempts for audit trail

### 4. API Security
- Use HTTPS for all API communications
- API key stored in configuration, never in views
- Sensitive data logged at appropriate levels

## Testing Checklist

### Manual Testing:

- [x] View unpaid invoice detail - shows Pay Now button
- [x] View paid invoice detail - shows transaction details
- [ ] Click Pay Now - redirects to Duitku payment page
- [ ] Complete payment - callback updates invoice status
- [ ] View button works for all invoices
- [ ] Back to invoices button works correctly
- [ ] Responsive design works on mobile devices
- [ ] Invoice items display correctly
- [ ] Transaction details display correctly
- [ ] Status badges display correctly (PAID/UNPAID)

### Automated Testing:
- [ ] Unit tests for DuitkuPayment library
- [ ] Unit tests for signature generation
- [ ] Unit tests for callback validation
- [ ] Integration tests for payment flow
- [ ] Controller tests for all new methods

## Duitku Sandbox Testing

### Test Cards (from Duitku documentation):
The sandbox environment allows testing without real payments. Use Duitku's test payment methods to simulate successful and failed payments.

### Important Notes:
- Sandbox mode is enabled by default (`$sandboxMode = true`)
- Callbacks in sandbox may not always work perfectly
- For production, change `$sandboxMode = false` and update credentials

## Troubleshooting

### Issue: Payment URL not generated
**Solution:** Check API credentials and merchant code. Verify network connectivity to Duitku sandbox.

### Issue: Callback not received
**Solution:** Ensure callback URL is publicly accessible. Check firewall settings. Verify webhook URL in Duitku dashboard.

### Issue: Signature validation fails
**Solution:** Verify API key is correct. Check signature generation formula matches Duitku documentation.

### Issue: Invoice status not updating
**Solution:** Check callback is being received. Verify signature validation passes. Check database permissions.

## Future Enhancements

### Recommended Additions:
1. **PDF Invoice Generation**
   - Use library like Dompdf or TCPDF
   - Generate PDF from invoice detail view
   - Add download functionality

2. **Email Notifications**
   - Send invoice to customer email
   - Send payment confirmation email
   - Send payment reminder for unpaid invoices

3. **Auto-check Payment Status**
   - Cron job to check pending payments
   - Query Duitku API for status updates
   - Update invoice status automatically

4. **Payment Method Selection**
   - Allow user to choose payment method
   - Display payment fees
   - Show available payment channels

5. **Refund Handling**
   - Add refund request functionality
   - Track refund status
   - Update transaction records

6. **Payment History**
   - Separate page showing all transactions
   - Filter by date, status, gateway
   - Export to CSV/Excel

## Support & Documentation

### Official Duitku Documentation:
- Website: https://duitku.com
- Sandbox Dashboard: https://sandbox.duitku.com
- API Documentation: https://docs.duitku.com

### CodeIgniter 4 Documentation:
- Website: https://codeigniter.com
- User Guide: https://codeigniter.com/user_guide/

## Changelog

### Version 1.0.0 (2025-11-20)
- Initial implementation of Duitku payment gateway
- Created invoice detail view with status detection
- Added payment callback handling
- Created database migrations for invoice_items and transactions
- Added test data seeders
- Updated invoice list with action buttons
- Added custom CSS styling for invoice pages

## License
This implementation follows the license of the main CodeIgniter Mini Dashboard project.
