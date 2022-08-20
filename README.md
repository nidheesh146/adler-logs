



// 
// die;
/*
@@ -0,0 +1,34 @@PURCHASE / SERVICE REQUESITIONPURCHASE / SERVICE REQUESITION - APPROVALQUOTATIONS QUOTATIONS - COMPARISIONFINAL PURCHASE ORDER FINAL WORK-ORDERMASTER - ASL (APPROVED SUPPLIER LIST - DIRECT)MASTER - ASL (APPROVED SUPPLIER LIST - IN-DIRECT)MASTER - PURCHASE / SERVICE ITEM MASTERITEM - CODE CREATIONMULTIPAL CURRENCY - PROVISIONMASTER - PRICE LIST - SUPPLIER WISEMATERIAL INWARD TO QUARANTINE (PO TO BE PULLED)LOT CARD MATERIAL ACCEPTANANCE & TRANSFER TO STORES (MIQ TO BE PULLED)MATERIAL REJECTION CUM DELIVERY NOTE (MIQ TO BE PULLED)   MATERIAL RECEIPT REPORT STOCK ISSUE TO PRODUCTION (WITH RATE / VALUE)STOCK RETURN FROM PRODUCTIONSTOCK TRANSFER ORDER - FOR ALL MATERIAL FROM STORES - SCRAP / RJECT / EXPIRY




-> FINAL PURCHASE ORDER  

LIST 
  PO number (system gen ) : | RQ number | Supplier quotation NO |  Supplier ID / name   |  PO date |  created_by  
Edit (master)
   PO date | created_by |    
->list item         
Edit :      -> DELIVERY SCHEDULE    testbox    
            -> order QTY -> copy  (SUPPLIER QTY)     
            -> Rate -> copy  (SUPPLIER rate)    
            -> Discount -> copy  (SUPPLIER rate)     
            -> Specification  -> copy  (SUPPLIER rate)


->  SUPPLIER invoice  ->
add :   -> PO number (select box) 
        -> invoice number (not system gen ) 
        -> SUPPLIER invoice date ( max today date ) 
        -> supplier details 
        -> created_by 
        -> date (currnt)     

->list item     

Edit :      
        -> order QTY -> copy  (SUPPLIER QTY)     
        -> Rate -> copy  (SUPPLIER rate)     
        -> Discount -> copy  (SUPPLIER rate)    
        -> Specification  -> copy  (SUPPLIER rate)

 -> LOT number allocation ->
 add :
    -> Supplier invoice number (select box)   
    -> supp invoice date    
    -> supp name    
    -> item details ( drop dowm )   
    -> Specification ( FINAL PURCHASE ORDER  Specification )   
    -> lot number (system gen)   
    -> invoice qty   
    -> qty recevied 

    */