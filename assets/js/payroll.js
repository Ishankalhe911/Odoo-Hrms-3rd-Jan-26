async function generate() {
  const fd = new FormData();
  fd.append("month", month.value);

  const res = await fetch(
    "http://localhost/dayflow-hrms/backend/index.php?module=payroll&action=generate",
    { method: "POST", body: fd, credentials: "include" }
  );

  alert(await res.text());
}
