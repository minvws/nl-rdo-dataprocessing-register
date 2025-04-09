alter table
  "processing_records"
add
  column "number" varchar(255) null;

alter table
  "processing_records"
add
  constraint "processing_records_number_unique" unique ("number");