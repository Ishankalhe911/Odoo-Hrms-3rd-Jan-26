const BASE_URL = "../backend/index.php";

document.getElementById("loginForm")?.addEventListener("submit", async (e) => {
  e.preventDefault();

  const form = new FormData();
  form.append("email", email.value);
  form.append("password", password.value);

  const res = await fetch(`${BASE_URL}?module=auth&action=login`, {
    method: "POST",
    body: form,
    credentials: "include"
  });

  if (!res.ok) {
    msg.innerText = "Invalid email or password";
    return;
  }

  // Simple redirect (backend enforces role anyway)
  window.location.href = "dashboard/employee.html";
});
