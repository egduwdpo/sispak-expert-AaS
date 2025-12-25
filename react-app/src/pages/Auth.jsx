import { useState } from "react";
import api from "../services/api";
import "../styles/auth.css";

export default function Auth() {
  const [isLogin, setIsLogin] = useState(true);

  const [loginData, setLoginData] = useState({
    email: "",
    password: "",
  });

  const [registerData, setRegisterData] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "user",
  });

  // LOGIN
  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const res = await api.post("/login", loginData);
      alert("Login berhasil");
      console.log(res.data);
    } catch (err) {
      alert("Login gagal");
    }
  };

  // REGISTER
  const handleRegister = async (e) => {
    e.preventDefault();
    try {
      const res = await api.post("/register", registerData);
      alert("Register berhasil");
      console.log(res.data);
      setIsLogin(true);
    } catch (err) {
      alert("Register gagal");
    }
  };

  return (
    <div className={`auth-container ${isLogin ? "" : "active"}`}>
      
      {/* REGISTER */}
      <div className="form-box register">
        <form onSubmit={handleRegister}>
          <h2>Registration</h2>

          <input type="text" placeholder="Nama"
            onChange={(e) => setRegisterData({...registerData, name: e.target.value})}
          />

          <input type="email" placeholder="Email"
            onChange={(e) => setRegisterData({...registerData, email: e.target.value})}
          />

          <input type="password" placeholder="Password"
            onChange={(e) => setRegisterData({...registerData, password: e.target.value})}
          />

          <input type="password" placeholder="Confirm Password"
            onChange={(e) => setRegisterData({...registerData, password_confirmation: e.target.value})}
          />

          <select
            onChange={(e) => setRegisterData({...registerData, role: e.target.value})}
          >
            <option value="user">User</option>
            <option value="pakar">Pakar</option>
          </select>

          <button type="submit">Register</button>
        </form>
      </div>

      {/* LOGIN */}
      <div className="form-box login">
        <form onSubmit={handleLogin}>
          <h2>Login</h2>

          <input type="email" placeholder="Email"
            onChange={(e) => setLoginData({...loginData, email: e.target.value})}
          />

          <input type="password" placeholder="Password"
            onChange={(e) => setLoginData({...loginData, password: e.target.value})}
          />

          <button type="submit">Login</button>
        </form>
      </div>

      {/* PANEL */}
      <div className="toggle-box">
        <div className="toggle-panel left">
          <h2>Hello, Welcome!</h2>
          <p>Don't have an account?</p>
          <button onClick={() => setIsLogin(false)}>Register</button>
        </div>

        <div className="toggle-panel right">
          <h2>Welcome Back!</h2>
          <p>Already have an account?</p>
          <button onClick={() => setIsLogin(true)}>Login</button>
        </div>
      </div>

    </div>
  );
}
