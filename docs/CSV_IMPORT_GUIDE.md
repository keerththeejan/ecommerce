# CSV Product Import Guide

## Overview
The enhanced CSV Product Import feature provides a modern, user-friendly interface for bulk importing products with comprehensive validation, error handling, and progress tracking.

## Features

### 1. Modern UI/UX
- **Drag & Drop Upload**: Intuitive file upload with visual feedback
- **Progress Bar**: Real-time upload and processing progress
- **File Validation**: Immediate client-side validation for file type and size
- **Data Preview**: Preview first 10 rows before importing
- **Responsive Design**: Works on desktop and mobile devices

### 2. CSV Validation
- **Required Columns**: `name`, `sku`, `price`, `stock_quantity`, `category_id`
- **Optional Columns**: `description`, `sale_price`, `price2`, `price3`, `brand_id`, `country_id`, `supplier`, `batch_number`, `status`, `add_date`, `expiry_date`, `tax_id`
- **Data Type Validation**: Numbers, dates, and enums are validated
- **SKU Uniqueness**: Prevents duplicate SKUs within the file and database
- **Foreign Key Validation**: Category IDs are verified against existing categories

### 3. Performance
- **Chunked Processing**: Large files are processed in batches of 100 rows
- **Transaction Safety**: Each batch is wrapped in a database transaction
- **Memory Efficient**: Streams large CSV files without loading entirely into memory
- **Batch Inserts**: Optimized for 10,000+ row files

### 4. Security
- **File Type Validation**: Only .csv files allowed
- **MIME Type Check**: Additional security layer
- **CSRF Protection**: All AJAX requests include CSRF tokens
- **Secure Uploads**: Files stored in randomized temporary paths
- **SQL Injection Prevention**: All queries use parameterized statements

### 5. Error Handling
- **Detailed Error Messages**: Row-by-row error reporting
- **Error Report Download**: Download failed rows as CSV
- **Partial Import Support**: Valid rows are imported even if some fail
- **Summary Dashboard**: Total, imported, and failed counts

### 6. Extra Features
- **Sample CSV Download**: Get a template with correct column headers
- **Import History**: View all past imports with undo option
- **Undo Import**: Rollback entire import batches (soft delete)
- **Help Tooltips**: Inline documentation for each column

## Usage

### 1. Prepare Your CSV
Download the sample CSV template to see the required format:
```
http://localhost/ecommerce/?controller=product&action=downloadSampleCsv
```

### 2. Upload
1. Go to **Products** → Click **Import CSV**
2. Drag & drop your CSV file or click to browse
3. Preview the first 10 rows
4. Click **Validate & Import**

### 3. Review Results
- Success: Shows count of imported products
- Errors: Download error report to fix issues
- History: View all imports and undo if needed

## CSV Format Specification

### Required Columns
| Column | Type | Description |
|--------|------|-------------|
| `name` | string | Product name (max 255 chars) |
| `sku` | string | Unique stock keeping unit (max 50 chars) |
| `price` | decimal | Base price (e.g., 99.99) |
| `stock_quantity` | integer | Available stock count |
| `category_id` | integer | Existing category ID |

### Optional Columns
| Column | Type | Description |
|--------|------|-------------|
| `description` | text | Product description |
| `sale_price` | decimal | Discounted price |
| `price2` | decimal | Wholesale/secondary price |
| `price3` | decimal | Tier 3 price |
| `brand_id` | integer | Existing brand ID |
| `country_id` | integer | Existing country ID |
| `supplier` | string | Supplier name |
| `batch_number` | string | Batch/lot number |
| `status` | enum | active, inactive, or out_of_stock |
| `add_date` | date | YYYY-MM-DD format |
| `expiry_date` | date | YYYY-MM-DD format |
| `tax_id` | integer | Tax class ID |

### Sample CSV Row
```csv
name,description,sku,price,sale_price,price2,price3,stock_quantity,category_id,brand_id,country_id,supplier,batch_number,status,add_date,expiry_date,tax_id
Premium Coffee,Organic arabica coffee beans,COFFEE001,24.99,19.99,22.99,21.99,150,3,1,1,Green Farms,2024A,active,2026-01-15,2027-01-15,1
```

## Technical Details

### File Structure
```
app/
├── controllers/
│   ├── ProductController.php (modified - added import methods)
│   └── ProductImportController.php (new - main import logic)
├── views/
│   └── admin/
│       └── products/
│           ├── csv_import_modal.php (new - UI component)
│           ├── import_history.php (new - history page)
│           └── index.php (modified - include new modal)
public/
└── uploads/
    └── products_import_template.csv (sample file)
docs/
└── CSV_IMPORT_GUIDE.md (this file)
```

### Database Table: product_import_logs
```sql
CREATE TABLE product_import_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    total_rows INT NOT NULL DEFAULT 0,
    imported_count INT NOT NULL DEFAULT 0,
    failed_count INT NOT NULL DEFAULT 0,
    imported_ids JSON,
    error_log TEXT,
    created_by INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    undone_at DATETIME NULL,
    undone_by INT NULL,
    INDEX idx_created_at (created_at),
    INDEX idx_created_by (created_by)
) ENGINE=InnoDB;
```

### API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `?controller=product&action=importCsvAjax` | POST | AJAX import endpoint |
| `?controller=product&action=downloadSampleCsv` | GET | Download template CSV |
| `?controller=product&action=undoImport&id=X` | POST | Undo specific import |
| `?controller=product&action=importHistory` | GET | View import history |

### Configuration
Maximum file size: 50MB (configurable in `ProductImportController::MAX_FILE_SIZE`)

Batch size: 100 rows (configurable in `ProductImportController::BATCH_SIZE`)

## Troubleshooting

### Common Errors

**"Missing required columns"**
- Ensure your CSV has all 5 required columns: name, sku, price, stock_quantity, category_id
- Check for typos in column headers (case-insensitive)

**"Duplicate SKU"**
- Each SKU must be unique across the entire database
- Check existing products or modify the SKU in your CSV

**"Invalid file type"**
- Only .csv files are accepted
- Ensure the file has a .csv extension

**"Category ID X does not exist"**
- Verify the category_id exists in the Categories table
- Use the admin panel to see valid category IDs

**"Import fails for large files"**
- Files are processed in chunks; if one chunk fails, others may succeed
- Check the error report for specific row failures

### Performance Tips
- For files > 5000 rows, consider splitting into smaller files
- Ensure all category_id, brand_id, country_id values exist before importing
- Remove unnecessary columns to reduce file size

## Support
For issues or questions about the CSV import feature, check the import history page for detailed error messages or contact the development team.
