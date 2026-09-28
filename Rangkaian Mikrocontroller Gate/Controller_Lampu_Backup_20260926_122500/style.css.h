#ifndef STYLE_CSS_H
#define STYLE_CSS_H

const char style_css[] PROGMEM = R"rawliteral(
body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f9;
  margin: 0;
  padding: 0;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.container {
  width: 50%;
  max-width: 500px;
  min-width: 280px;
  padding: 1rem;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
}

h1 {
  color: #444;
  font-size: 2rem;
  margin-bottom: 1rem;
  text-align: center;
}

form {
  width: 100%;
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;
}

label {
  margin-bottom: 0.5rem;
  font-size: 1rem;
  color: #555;
}

select, input[type="password"], input[type="text"] {
  width: 100%;
  padding: 10px;
  margin-bottom: 1rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1rem;
  background-color: #f9f9f9;
  color: #333;
  outline: none;
}

select:focus, input:focus {
  border-color: #2196F3;
}

button {
  background-color: #2196F3;
  color: white;
  padding: 12px 24px;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  width: 100%;
  margin-top: 0.5rem;
}

button:hover {
  background-color: #1976D2;
}

button:active {
  background-color: #1565C0;
}

button.btn-back {
  background-color: #777;
}

button.btn-back:hover {
  background-color: #555;
}

button.btn-back:active {
  background-color: #333;
}

.password-container {
  position: relative;
  display: inline-block;
}

.password-container input[type="password"],
.password-container input[type="text"] {
  padding-right: 30px; /* space for the icon */
}

.toggle-password {
  position: absolute;
  right: 8px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  font-size: 18px;
}


@media (max-width: 600px) {
  .container {
    width: 90%;
  }

  h1 {
    font-size: 1.5rem;
  }
}
)rawliteral";

#endif
