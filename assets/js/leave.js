document.getElementById("leaveForm")?.addEventListener("submit", async e => {
  e.preventDefault();

  const f = new FormData();
  f.append("start_date", start.value);
  f.append("end_date", end.value);
  f.append("type", type.value);

  const res = await fetch(
    "../../backend/index.php?module=leave&action=apply",
    { method: "POST", body: f, credentials: "include" }
  );

  msg.innerText = await res.text();
});
