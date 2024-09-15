# Changelog

## V1

#### Users
- Replaced email verification with setting initial password upon registration.
- Added "affiliate" user type.
- Added search function on activity log.
- Improved profile UI.
- Improved user list UI.
- Improved user filtering.


#### Products
- Moved brand specification to cart, quotation, and purchase order to prevent duplicate product listing per brand.
- Improved form UI.
- Improved product list UI.
- Improved product filtering.

#### Quotations
- Added reference number.
- Replaced product id reference on database with product data to maintain integrity even if product information is changed.
- Improved form UI.
- Improved quotation list UI.
- Improved quotation filtering.
- Affiliate users may only view their own quotations.

#### Purchase Orders
- Added a copy of quotation items marked as "available" to maintain data integrity. This allows changes on purchase order without affecting the quotation.
- Improved form UI.
- Improved purchase order list UI.
- Improved purchase order filtering.
- Affiliate users may only view their own purchase orders.

#### Other Changes
- Integrated Laravel Precognition package. This allows forms to be validated before submission.
- Added form changes detection. 
- Added a field on records to tracking which user was the last to modify that record.
- Unified layout for simplicity.
- Integrated Maizzle for better email template generation.
