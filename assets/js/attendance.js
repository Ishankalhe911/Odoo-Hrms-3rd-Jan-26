async function check() {
  const res = await fetch(
    "../../backend/index.php?module=attendance&action=checkin_checkout",
    { credentials: "include" }
  );

  const text = await res.text();
  msg.innerText = text;
}
