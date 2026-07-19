from pathlib import Path
import re

p = Path(r"C:\wamp64\www\ecommerce\app\views\admin\products\create.php")
t = p.read_text(encoding="utf-8", errors="replace")
ids = sorted(set(re.findall(r'id="([^"]+)"', t)))
names = sorted(set(re.findall(r'name="([^"]+)"', t)))
funcs = sorted(set(re.findall(r'function\s+([A-Za-z0-9_]+)', t)))
Path(r"C:\wamp64\www\ecommerce\storage\cache\_pc_ids.txt").write_text(
    "IDS\n" + "\n".join(ids) + "\n\nNAMES\n" + "\n".join(names) + "\n\nFUNCS\n" + "\n".join(funcs),
    encoding="utf-8"
)
# Find key slices
style_end = t.find("</style>")
form_start = t.find('<form id="productForm"')
# Find first script after form
scripts_start = t.find("<script>", form_start)
print("style_end", style_end)
print("form_start", form_start)
print("scripts_start", scripts_start)
print("total_len", len(t))
print("ids", len(ids), "names", len(names), "funcs", len(funcs))
# Save form+after for reference (from form to end)
Path(r"C:\wamp64\www\ecommerce\storage\cache\_pc_tail.php").write_text(t[form_start:], encoding="utf-8")
print("tail_bytes", len(t[form_start:]))
