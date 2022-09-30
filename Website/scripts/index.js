import { deleteCookie, getCookie, setCookie } from "./modules/cookie.js";

let data = null;

async function start() {
  try {
    const response = await fetch(
      `http://localhost:3200/verify_auth_token.php`,
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ auth_token: getCookie("authToken") }),
      }
    );
    if (response.status >= 401 && response.status <= 599) {
      throw new Error(await response.text());
    }
    data = await response.json();
    console.log("Response is : ", data);
    document.getElementById("name").innerHTML = data.name;
  } catch (err) {
    console.log(err.message);
    console.log(typeof err);
    if (err.message.match(/Authorization Token is Invalid/i)) {
      deleteCookie("authToken");
    }
  }
}
if (getCookie("authToken") == "") {
  window.location.replace("login.html");
}
start();
document.getElementById("submit").addEventListener("click", changePassword);

async function changePassword() {
  try {
    const password = document.getElementById("password").value;
    const auth_token = getCookie("authToken");
    if (password == "") {
      throw new Error("Password is empty");
    }
    const response = await (
      await fetch(`http://localhost:3200/change_password.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ password, auth_token }),
      })
    ).text();
    document.getElementById("password-update-message").style.display = "block";
    document.getElementById("password-update-message").innerHTML = response;
  } catch (err) {
    console.log(err);
    document.getElementById("password-update-message").style.display = "block";
    document.getElementById("password-update-message").innerHTML = err.message;
  }
  setTimeout(() => {
    document.getElementById("password-update-message").style.display = "none";
  }, 1300);
}
