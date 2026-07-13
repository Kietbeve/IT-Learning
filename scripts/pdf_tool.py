import sys
import json
import fitz
import os
import math

def add_watermark(input_path, output_path, watermark_text):
    try:
        doc = fitz.open(input_path)
        for page in doc:
            rect = page.rect
            text_length = fitz.get_text_length(watermark_text, fontname="helv", fontsize=20)
            
            # Place at top center
            x = (rect.width - text_length) / 2
            y = 30
            
            page.insert_text(
                fitz.Point(x, y),
                watermark_text,
                fontsize=20,
                fontname="helv",
                color=(0.5, 0.5, 0.5), # Gray
                fill_opacity=0.5
            )
            
        doc.save(output_path, encryption=fitz.PDF_ENCRYPT_KEEP, permissions=~fitz.PDF_PERM_PRINT)
        doc.close()
        return True
    except Exception as e:
        print(f"Error watermarking: {e}", file=sys.stderr)
        return False

def generate_preview(input_path, output_path):
    try:
        doc = fitz.open(input_path)
        page_count = len(doc)
        
        if page_count < 4:
            preview_pages = 1
        elif page_count <= 6:
            preview_pages = 1
        else:
            preview_pages = max(2, min(5, math.ceil(page_count * 0.2)))
            
        preview_doc = fitz.open()
        preview_doc.insert_pdf(doc, from_page=0, to_page=preview_pages - 1)
        preview_doc.save(output_path)
        preview_doc.close()
        doc.close()
        return True
    except Exception as e:
        print(f"Error generating preview: {e}", file=sys.stderr)
        return False

if __name__ == "__main__":
    if len(sys.argv) < 4:
        print(json.dumps({"success": False, "error": "Missing arguments"}))
        sys.exit(1)
        
    action = sys.argv[1]
    input_path = sys.argv[2]
    output_path = sys.argv[3]
    
    if not os.path.exists(input_path):
        print(json.dumps({"success": False, "error": "Input file not found"}))
        sys.exit(1)
        
    result = False
    
    if action == "watermark":
        watermark_text = sys.argv[4] if len(sys.argv) > 4 else "Watermark"
        result = add_watermark(input_path, output_path, watermark_text)
    elif action == "preview":
        result = generate_preview(input_path, output_path)
        
    print(json.dumps({"success": result}))
