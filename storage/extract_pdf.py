from pdfminer.high_level import extract_text
text = extract_text('/var/www/html/FE & BE (Web Team) Hackathon 2.0.pdf')
print(text)
