# Changelog

## [1.0.3]
### Fixed
- Added Lamination Type in View order
- Updated Ordered Book Model to take lamination type
- Updated ordered book page

## [1.0.3]
### Added
- Add View Features For Order
- Add Delet Features For Order
- View Cover Type in Design Queue
- Add Lamination Type Property for Ordered Books

### Changed
- Show Cover Print status in Binding Queue in stead of Cover Print/Print queue


## [1.0.2] - 2025-06-10
### Added
- Add Updating Features For Order
- Add Order Invoice Generation


## [1.0.1] - 2025-06-08
### Added
- Add Different Color according to Status
- Add Multiple Status Updating Feature

### Fixed
- Fix Responsive Design for Queue Tables
- Fix Pre Designed Cover Printing Updating
- Fix Ordered Book All status Updating


## [1.0.0] - 2025-06-05

### Added
- Initial release with full functionality.
- User registration and login system.
- Multiple queues management added: Design Queue, Printing Queue, Cover Printing Queue, Binding Queue, QC Queue, Packaging Queue, Shipment Queue.
- Automated order creation including books, and automatic linking of books to various queues.
- Middleware for preventing access to pages for guests and restricting logged-in users from viewing the login page.
- Flash messages for success notifications (e.g., status updates in queues).
  
### Changed
- Refined order creation with auto-generated order IDs and book IDs.
- Validation for order creation forms to prevent incorrect data input.
- Added logic to automatically update book statuses based on related queue statuses (Design, Printing, etc.).
- Grand total price calculations now consider unit price, quantity, delivery charge, and discount.

### Fixed
- Fixed the issue where book statuses were not being updated correctly across all queues.
- Corrected bug in status management across various queues (Design, Printing, etc.).
- Fixed display issues where related queue statuses were not properly reflected in the order and book status.

### Security
- Enhanced security for user login with proper middleware in place.
- Improved encryption for stored passwords.
