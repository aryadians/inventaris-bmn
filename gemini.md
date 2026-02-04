# SIMA: Sistem Inventaris BMN (Gemini Context)

## 📌 Project Overview
**SIMA (Sistem Informasi Manajemen Aset)** is a web-based application designed for managing State-Owned Assets (BMN - Barang Milik Negara), specifically tailored for **Lapas Kelas IIB Jombang**. Ideally, it serves to digitalize asset management, automate depreciation calculations, track borrowing/maintenance, and generate reports.

*   **Author**: Arya Dian (`aryadians`)
*   **Repository**: [aryadians/inventaris-bmn](https://github.com/aryadians/inventaris-bmn)
*   **License**: MIT

## 🛠 Tech Stack

### Backend
*   **Framework**: Laravel 12.x (PHP ^8.2)
*   **Admin Panel**: FilamentPHP v3.x
*   **Authentication & Permissions**: Filament Shield (`bezhansalleh/filament-shield`)

### Frontend
*   **Templating**: Blade Scaffolding (standard Laravel)
*   **Styling**: Tailwind CSS v4.x (`@tailwindcss/vite`)
*   **Bundler**: Vite

### Database & Storage
*   **Database**: MySQL 8.0
*   **Storage**: Local storage (public disk linked)

### Key Libraries/Packages
*   `filament/filament`: Admin panel and UI components.
*   `maatwebsite/excel`: Excel file Import/Export.
*   `barryvdh/laravel-dompdf`: PDF generation for reports/labels.
*   `simplesoftwareio/simple-qrcode`: QR Code generation for asset labeling.

## 📂 Project Structure

*   `app/Filament`: Contains all Filament resources, pages, and widgets. This is the core of the application logic.
    *   `Resources`: CRUD interfaces for models (e.g., AssetResource, LoanResource).
*   `app/Models`: Eloquent models (Asset, Room, Category, etc.).
*   `database/migrations`: Database schema definitions.
*   `config`: Configuration files (standard Laravel).
*   `public/`: Publicly accessible assets.
*   `resources/views`: Blade templates (mostly for custom PDF exports or specific non-Filament pages).

## 🌟 Key Features & Domain Models

### 1. Asset Management (`Asset`)
*   **Core Entity**: Tracks items with details like brand, type, acquisition date, price, and condition.
*   **Depreciation**: Automatic calculation using "Straight Line Method" based on "Useful Life" (Masa Manfaat).
    *   Formula: `Depreciation/Year = Acquisition Price / Useful Life`
*   **Identification**: QR Code generation for physical tagging.

### 2. Inventory Organization
*   **Categories (`Category`)**: Classification of assets.
*   **Rooms (`Room`)**: Physical locations of assets.
*   **Mutations**: Tracking movement/transfer of assets between rooms or status changes.

### 3. Operational Features
*   **Lending/Borrowing**: Tracks who borrowed what and when due.
    *   Includes automated notifications/reminders.
*   **Maintenance**: History of repairs and service logs for assets.
*   **Stock Opname**: Periodic inventory checking and reconciliation.

### 4. Reporting
*   **Export**: Data portability via Excel.
*   **PDF**: Printable reports and QR labels.

## 🚀 Setup & Development

### Prerequisites
*   PHP >= 8.2
*   Composer
*   Node.js & NPM
*   MySQL

### Installation
1.  **Clone & Install Dependencies**:
    ```bash
    git clone https://github.com/aryadians/inventaris-bmn.git
    cd inventaris-bmn
    composer install
    npm install
    ```
2.  **Environment Setup**:
    ```bash
    cp .env.example .env
    # Configure DB credentials in .env
    php artisan key:generate
    ```
3.  **Database Setup**:
    ```bash
    php artisan migrate --seed
    ```
4.  **Asset Linking**:
    ```bash
    php artisan storage:link
    php artisan filament:optimize
    ```
5.  **Run Application**:
    ```bash
    # Run backend and frontend build in parallel
    npm run dev
    # OR serve PHP separately
    php artisan serve
    ```

## 📝 Coding Conventions
*   **Laravel Standards**: Follow PSR-12 and standard Laravel naming conventions.
*   **Filament Patterns**: Use Resources for CRUD. Use Actions for specific tasks (like Export/Print).
*   **Language**: Codebase primarily uses English for code (variables, functions) but Indonesian for UI labels/text (as seen in `lang/` and README).

## 🔍 Common Commands
*   `php artisan make:filament-resource [Model] --generate`: Create a new Filament CRUD resource.
*   `php artisan shield:generate`: Regenerate permission policies.
