alter table
  "avg_processor_processing_records"
add
  column "review_at" date null;

alter table
  "avg_responsible_processing_records"
add
  column "review_at" date null;

alter table
  "wpg_processing_records"
add
  column "review_at" date null;