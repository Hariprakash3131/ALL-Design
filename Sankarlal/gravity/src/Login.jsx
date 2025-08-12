import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './Login.css';
import './Front.jsx'

export default function Login() {
  const [form, setForm] = useState({ username: '', password: '' });
  const navigate = useNavigate();

  const handleChange = e => setForm({ ...form, [e.target.name]: e.target.value });

  const handleSubmit = async e => {
    e.preventDefault();
    const response = await fetch('http://localhost:8000/api/login/', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form)
    });
    const data = await response.json();
    if (response.ok) {
      // Save token if you want to use it later
      localStorage.setItem('access', data.access);
      alert('Login successful!');
      navigate('/front'); // Redirect to Front.jsx
    } else {
      alert(data.error || 'Login failed');
    }
  };

  return (
    <div className="login-root">
      <form className="login-form" onSubmit={handleSubmit}>
        <h2 className="login-title">Login</h2>
        <label>
          Username
          <input
            type="text"
            name="username"
            value={form.username}
            onChange={handleChange}
            placeholder="Username"
            required
          />
        </label>
        <label>
          Password
          <input
            type="password"
            name="password"
            value={form.password}
            onChange={handleChange}
            placeholder="Password"
            required
          />
        </label>
        <button className="login-btn" type="submit">Login</button>
        <div className="login-signup-link">
          Don't have an account?{' '}
          <span className="signup-link" onClick={() => navigate('/signup')}>Sign Up</span>
        </div>
      </form>
    </div>
  );
} 