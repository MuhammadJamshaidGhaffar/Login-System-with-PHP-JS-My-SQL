import { setCookie, getCookie } from "./modules/cookie.js";

async function signUp() {
  try {
    const name = document.getElementById("name").value;
    const password = document.getElementById("password").value;
    if (name == "" || password == "") {
      throw new Error("Name or Password is empty");
    }
    const authToken = await (
      await fetch(`http://localhost:3200/signup.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, password }),
      })
    ).text();
    if (!authToken.match(/error/i)) {
      console.log("Response is : ", authToken);
      setCookie("authToken", authToken, 15);
      window.location.replace("index.html");
    } else {
      console.log("Failed \n", authToken);
    }
  } catch (err) {
    console.log(err);
  }
}
if (getCookie("authToken") != "") {
  window.location.replace("index.html");
} else {
  document.getElementById("submit").addEventListener("click", signUp);
}

console.log("hello Welocme to signup page");
