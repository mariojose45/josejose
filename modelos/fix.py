import re
path = 'c:\\xampp\\htdocs\\josejose\\modelos\\Articulo.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()
# Replace ROUND(..., 2) with ROUND(..., 6)
content = re.sub(r'ROUND\(([^,]+),\s*2\)', r'ROUND(\1, 6)', content)
with open(path, 'w', encoding='utf-8') as f:
    f.write(content)
