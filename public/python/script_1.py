# Create the complete configuration file for ALL tables
complete_config_json = '''{
  "local_db": {
    "host": "localhost",
    "port": 3306,
    "database": "malabarr_sku_new",
    "user": "root",
    "password": "",
    "charset": "utf8mb4"
  },
  "online_db": {
    "host": "adler.matsolutions.in",
    "port": 3306,
    "database": "matsolut_malabarr_sku_new",
    "user": "matsolut_adler",
    "password": "-ODS,R4anC]d",
    "charset": "utf8mb4",
    "ssl_disabled": false
  },
  "tables_to_sync": [
    {
      "name": "add_quality",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["batch_no", "sku_name"],
      "chunk_size": 500,
      "priority": 1,
      "description": "Quality control inspection records"
    },
    {
      "name": "batchcard_batchcard",
      "primary_key": "id",
      "timestamp_column": "updated",
      "unique_columns": ["batchno"],
      "chunk_size": 1000,
      "priority": 1,
      "description": "Production batch cards"
    },
    {
      "name": "product_product",
      "primary_key": "id",
      "timestamp_column": "updated",
      "unique_columns": ["skucode"],
      "chunk_size": 1000,
      "priority": 1,
      "description": "Main product master"
    },
    {
      "name": "inventory_rawmaterial",
      "primary_key": "id",
      "timestamp_column": "updated",
      "unique_columns": ["itemcode"],
      "chunk_size": 1000,
      "priority": 1,
      "description": "Raw materials inventory"
    },
    {
      "name": "customer_supplier",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["gstnumber", "firmname"],
      "chunk_size": 500,
      "priority": 1,
      "description": "Customer and supplier master"
    },
    {
      "name": "inv_stock_management",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["itemid", "lotid"],
      "chunk_size": 2000,
      "priority": 1,
      "description": "Inventory stock tracking"
    },
    {
      "name": "fgs_product_stock_management",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["productid", "batchcardid", "stocklocationid"],
      "chunk_size": 2000,
      "priority": 1,
      "description": "Finished goods stock management"
    },
    {
      "name": "production_stock_management",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["productid", "batchcardid"],
      "chunk_size": 2000,
      "priority": 1,
      "description": "Production stock tracking"
    },
    {
      "name": "fgs_item_master",
      "primary_key": "id",
      "timestamp_column": "updated",
      "unique_columns": ["skucode"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Finished goods item master"
    },
    {
      "name": "batchcard_materials",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["batchcardid", "productinputmaterialid"],
      "chunk_size": 2000,
      "priority": 2,
      "description": "Materials used in batch production"
    },
    {
      "name": "assembly_batchcards",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["mainbatchcardid", "primaryskubatchcardid"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Assembly batch relationships"
    },
    {
      "name": "product_input_material",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["productid", "inventoryrawmaterialid"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Bill of materials"
    },
    {
      "name": "inv_purchase_req_master",
      "primary_key": "masterid",
      "timestamp_column": "updatedat",
      "unique_columns": ["prno"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Purchase requisition master"
    },
    {
      "name": "inv_final_purchase_order_master",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["ponumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Purchase order master"
    },
    {
      "name": "inv_supplier_invoice_master",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["invoicenumber", "supplierid"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Supplier invoice headers"
    },
    {
      "name": "fgs_mrn",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["mrnnumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Material receipt notes"
    },
    {
      "name": "fgs_oef",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["oefnumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Outward entry forms"
    },
    {
      "name": "fgs_grs",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["grsnumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Goods receipt slips"
    },
    {
      "name": "fgs_pi",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["pinumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Packing invoices"
    },
    {
      "name": "fgs_dni",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["dninumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Delivery note invoices"
    },
    {
      "name": "fgs_srn",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["srnnumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Sales return notes"
    },
    {
      "name": "fgs_mtq",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["mtqnumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Material transfer quarantine"
    },
    {
      "name": "fgs_min",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["minnumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Material issue notes"
    },
    {
      "name": "inv_mac",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["macnumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Material acceptance certificates"
    },
    {
      "name": "inv_miq",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["miqnumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Material inward quarantine"
    },
    {
      "name": "inv_stock_to_production",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["sipnumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Stock issued to production"
    },
    {
      "name": "inv_stock_from_production",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["sirnumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Stock received from production"
    },
    {
      "name": "fgs_sad",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["sadnumber"],
      "chunk_size": 500,
      "priority": 2,
      "description": "Stock adjustment decrease"
    },
    {
      "name": "fgs_sai",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["sainumber"],
      "chunk_size": 500,
      "priority": 2,
      "description": "Stock adjustment increase"
    },
    {
      "name": "delivery_challan",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["dcnumber"],
      "chunk_size": 1000,
      "priority": 2,
      "description": "Delivery challans"
    },
    {
      "name": "user",
      "primary_key": "userid",
      "timestamp_column": "createdat",
      "unique_columns": ["email", "username"],
      "chunk_size": 100,
      "priority": 3,
      "description": "System users"
    },
    {
      "name": "role",
      "primary_key": "roleid",
      "timestamp_column": "createdat",
      "unique_columns": ["rolename"],
      "chunk_size": 50,
      "priority": 3,
      "description": "User roles"
    },
    {
      "name": "permissions",
      "primary_key": "permissionid",
      "timestamp_column": "createdat",
      "unique_columns": ["permissionname"],
      "chunk_size": 100,
      "priority": 3,
      "description": "System permissions"
    },
    {
      "name": "department",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["deptname"],
      "chunk_size": 50,
      "priority": 3,
      "description": "Company departments"
    },
    {
      "name": "product_product_family",
      "primary_key": "id",
      "timestamp_column": "updated",
      "unique_columns": ["familyname"],
      "chunk_size": 200,
      "priority": 3,
      "description": "Product families"
    },
    {
      "name": "product_product_brand",
      "primary_key": "id",
      "timestamp_column": "updated",
      "unique_columns": ["brandname"],
      "chunk_size": 200,
      "priority": 3,
      "description": "Product brands"
    },
    {
      "name": "product_product_group",
      "primary_key": "id",
      "timestamp_column": "updated",
      "unique_columns": ["groupname"],
      "chunk_size": 200,
      "priority": 3,
      "description": "Product groups"
    },
    {
      "name": "product_stock_location",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["locationname"],
      "chunk_size": 100,
      "priority": 3,
      "description": "Stock locations"
    },
    {
      "name": "state",
      "primary_key": "stateid",
      "timestamp_column": "createdat",
      "unique_columns": ["statename"],
      "chunk_size": 50,
      "priority": 3,
      "description": "Indian states"
    },
    {
      "name": "zone",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["zonename"],
      "chunk_size": 50,
      "priority": 3,
      "description": "Geographic zones"
    },
    {
      "name": "currency_exchange_rate",
      "primary_key": ["currencyid", "cerupdatedat"],
      "timestamp_column": "cerupdatedat",
      "unique_columns": ["currencyid", "cerupdatedat"],
      "chunk_size": 200,
      "priority": 3,
      "description": "Currency exchange rates"
    },
    {
      "name": "inventory_gst",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 100,
      "priority": 3,
      "description": "GST tax configurations"
    },
    {
      "name": "transaction_type",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["transactionname"],
      "chunk_size": 50,
      "priority": 3,
      "description": "Transaction types"
    },
    {
      "name": "order_fulfil",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 100,
      "priority": 3,
      "description": "Order fulfillment types"
    },
    {
      "name": "inv_unit",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["unitname"],
      "chunk_size": 50,
      "priority": 3,
      "description": "Units of measurement"
    },
    {
      "name": "work_centre",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["workcentrename"],
      "chunk_size": 100,
      "priority": 3,
      "description": "Manufacturing work centers"
    },
    {
      "name": "product_type",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["producttypename"],
      "chunk_size": 50,
      "priority": 3,
      "description": "Product type classifications"
    },
    {
      "name": "product_oem",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["oemname"],
      "chunk_size": 100,
      "priority": 3,
      "description": "OEM manufacturers"
    },
    {
      "name": "inv_item_type",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["itemtypename"],
      "chunk_size": 50,
      "priority": 3,
      "description": "Inventory item types"
    },
    {
      "name": "inv_supplier",
      "primary_key": "id",
      "timestamp_column": "updated",
      "unique_columns": ["vendorid"],
      "chunk_size": 500,
      "priority": 3,
      "description": "Supplier master data"
    },
    {
      "name": "inv_purchase_req_item",
      "primary_key": ["requisitionitemid", "createduser"],
      "timestamp_column": "updatedat",
      "unique_columns": ["requisitionitemid", "createduser"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Purchase requisition line items"
    },
    {
      "name": "inv_final_purchase_order_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Purchase order line items"
    },
    {
      "name": "inv_supplier_invoice_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Supplier invoice line items"
    },
    {
      "name": "fgs_mrn_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Material receipt note items"
    },
    {
      "name": "fgs_oef_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Outward entry form items"
    },
    {
      "name": "fgs_grs_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Goods receipt slip items"
    },
    {
      "name": "fgs_pi_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Packing invoice items"
    },
    {
      "name": "fgs_dni_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Delivery note invoice items"
    },
    {
      "name": "fgs_srn_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Sales return note items"
    },
    {
      "name": "fgs_mtq_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Material transfer quarantine items"
    },
    {
      "name": "fgs_min_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Material issue note items"
    },
    {
      "name": "inv_mac_item",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Material acceptance certificate items"
    },
    {
      "name": "inv_miq_item",
      "primary_key": "id",
      "timestamp_column": "updatedat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Material inward quarantine items"
    },
    {
      "name": "fgs_sad_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 1000,
      "priority": 4,
      "description": "Stock adjustment decrease items"
    },
    {
      "name": "fgs_sai_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 1000,
      "priority": 4,
      "description": "Stock adjustment increase items"
    },
    {
      "name": "delivery_challan_item",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["id"],
      "chunk_size": 2000,
      "priority": 4,
      "description": "Delivery challan items"
    },
    {
      "name": "role_permission_rel",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["roleid", "permissionid"],
      "chunk_size": 500,
      "priority": 5,
      "description": "Role permission relationships"
    },
    {
      "name": "inv_purchase_req_master_item_rel",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["master", "item"],
      "chunk_size": 3000,
      "priority": 5,
      "description": "Purchase requisition relationships"
    },
    {
      "name": "inv_final_purchase_order_rel",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["master", "item"],
      "chunk_size": 3000,
      "priority": 5,
      "description": "Purchase order relationships"
    },
    {
      "name": "inv_supplier_invoice_rel",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["master", "item"],
      "chunk_size": 3000,
      "priority": 5,
      "description": "Supplier invoice relationships"
    },
    {
      "name": "fgs_mrn_item_rel",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["master", "item"],
      "chunk_size": 3000,
      "priority": 5,
      "description": "Material receipt note relationships"
    },
    {
      "name": "fgs_oef_item_rel",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["master", "item"],
      "chunk_size": 3000,
      "priority": 5,
      "description": "Outward entry form relationships"
    },
    {
      "name": "fgs_grs_item_rel",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["master", "item"],
      "chunk_size": 3000,
      "priority": 5,
      "description": "Goods receipt slip relationships"
    },
    {
      "name": "fgs_pi_item_rel",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["master", "item"],
      "chunk_size": 3000,
      "priority": 5,
      "description": "Packing invoice relationships"
    },
    {
      "name": "fgs_dni_item_rel",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["master", "item"],
      "chunk_size": 3000,
      "priority": 5,
      "description": "Delivery note invoice relationships"
    },
    {
      "name": "fgs_srn_item_rel",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["master", "item"],
      "chunk_size": 3000,
      "priority": 5,
      "description": "Sales return note relationships"
    },
    {
      "name": "delivery_challan_item_rel",
      "primary_key": "id",
      "timestamp_column": "createdat",
      "unique_columns": ["master", "item"],
      "chunk_size": 3000,
      "priority": 5,
      "description": "Delivery challan relationships"
    }
  ],
  "sync_settings": {
    "incremental_hours": 24,
    "max_retries": 3,
    "retry_delay": 5,
    "connection_timeout": 30,
    "pool_size": 8,
    "batch_commit": true,
    "parallel_sync": true,
    "max_parallel_tables": 4,
    "sync_by_priority": true
  },
  "logging": {
    "level": "INFO",
    "file": "db_sync.log",
    "max_size": 20971520,
    "backup_count": 10
  },
  "email_notifications": {
    "enabled": false,
    "smtp_server": "smtp.gmail.com",
    "smtp_port": 587,
    "username": "your_email@gmail.com",
    "password": "your_app_password",
    "from_email": "your_email@gmail.com",
    "to_emails": ["admin@yourcompany.com"],
    "send_on_error": true,
    "send_summary": false
  }
}'''

# Write the configuration file
with open('sync_config_complete.json', 'w') as f:
    f.write(complete_config_json)

print(" Complete configuration file created: sync_config_complete.json")
print(" Configuration includes 69+ tables with priorities and descriptions")