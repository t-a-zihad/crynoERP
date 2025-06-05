# Crynoverse ERP – Version 1

## Overview

This is a custom ERP system tailored for Crynoverse’s print-on-demand book operations. It streamlines order tracking, production workflows, quality control, inventory, and packaging, eliminating the inefficiencies of using Excel, Messenger, and ad hoc communication.

---

## 🚩 Problems This ERP Solves

- **No centralized tracking**: Orders previously scattered across platforms.
- **Unclear production stages**: Uncertainty over where books are in the pipeline.
- **Binding return chaos**: Manual and error-prone matching of bound books to orders.
- **Forgotten books**: Books stuck in shelves get lost or delayed.
- **Missed/failed prints**: Issues noticed too late in packaging.
- **Untracked customizations**: Gifts or special requests hard to monitor.

---

## 🧩 Key Features & Modules

### ✅ Module 1: Order Management

Tracks customer orders and manages each book's production lifecycle:

- **Master Book Catalog**: Metadata for all books (title, code, price, links).
- **Customer Orders**: Multi-book order records, delivery details, manager assignment, and status tracking.
- **Book Items**: Each book is tracked through stages—print, design, binding, QC—with individual statuses.

### 📊 Dashboard View

Displays a summary table of all orders with:

- Order status
- Assigned manager
- Book count and prices
- Progress tracking
- Deep link to item-level views

### 🔁 Full Book Production Workflow

1. **Order Entry**
2. **Book Printing**
3. **Cover Design**
4. **Cover Printing**
5. **Binding (In/Out)**
6. **QC (Quality Check)**
7. **Packaging & Dispatch**

Each phase includes manual or semi-automated status transitions and auto-updates order progress in real-time.

---

## 🔒 Role-Based Access (To Be Finalized)

| Role          | Create | Edit | View | Assign | Cost View |
|---------------|--------|------|------|--------|-----------|
| Admin         | ✔      | ✔    | ✔    | ✔      | ✔         |
| Order Manager | ✔      | ✔    | ✔    | ✔      | ✔         |
| Print Team    | ✖      | ✖    | ✔    | ✖      | ✖         |
| Design Team   | ✖      | ✖    | ✔    | ✖      | ✖         |

---

## 🔮 Planned Features & Adaptability

| Feature                            | Description                                                                 | Complexity |
|-----------------------------------|-----------------------------------------------------------------------------|------------|
| Sorting on all pages              | By date, status, and custom periods                                        | Easy       |
| Bulk update of status             | Change status of multiple books at once                                    | Easy       |
| Export to DOCX/CSV                | Export filtered lists (e.g., Binding Out List, employee task logs)         | Easy       |
| Audit trail per book              | Logs of all updates with timestamps and user info                          | Easy       |
| Defect/Reject Management          | Alerts and flagging for issues at any stage                                | Medium     |
| Dashboard Overview                | Metrics like active orders, inventory, QC queue (count & amount)           | Medium     |
| Custom Dept. Tasks                | Assign issue-specific notes (e.g., damaged cover) with visibility options  | Medium     |
| Inventory Integration             | Automatically match inventory to order fulfillment                         | Medium     |
| Accounting Module                 | (Planned, details TBD)                                                     | TBD        |

---

## 👩‍💼 User Info & Authentication

Site users (employees) have:

- Unique ID
- Name, email, contact number
- Role-based access
- Job logs and task tracking

---

## 🛠️ Next Updates

- Enhanced order update features
- Centralized "All Book Status" page
- Pagination in views
- Employee info edit
- Foreign key relationships in DB schema

---

## 📦 Technologies Suggested

- Backend: Laravel / Node.js
- Frontend: Vue.js / React
- Database: MySQL / PostgreSQL
- Authentication: Role-based middleware

---

## License

Internal use only — proprietary to Crynoverse. Redistribution or commercial use is prohibited without permission.
