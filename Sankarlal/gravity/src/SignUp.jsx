import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './SignUp.css';

export default function SignUp() {
  const [form, setForm] = useState({
    username: '',
    email: '',
    role: 'patient',
    password: ''
  });
  const navigate = useNavigate();

  const handleChange = e => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async e => {
    e.preventDefault();
    const response = await fetch('http://localhost:8000/api/register/', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form)
    });
    const data = await response.json();
    if (response.ok) {
      alert('Sign up successful!');
      navigate('/login');
    } else {
      alert(data.error || 'Sign up failed');
    }
  };

  return (
    <div className="signup-root">
      <form className="signup-form" onSubmit={handleSubmit}>
        <h2 className="signup-title">Create an Account</h2>
        <label>
          Username
          <input
            type="text"
            name="username"
            value={form.username}
            onChange={handleChange}
            required
            autoComplete="username"
          />
        </label>
        <label>
          Email
          <input
            type="email"
            name="email"
            value={form.email}
            onChange={handleChange}
            required
            autoComplete="email"
          />
        </label>
        <label>
          Role
          <select
            name="role"
            value={form.role}
            onChange={handleChange}
            required
          >
            <option value="patient">Patient</option>
            <option value="caretaker">Caretaker</option>
          </select>
        </label>
        <label>
          Password
          <input
            type="password"
            name="password"
            value={form.password}
            onChange={handleChange}
            required
            autoComplete="new-password"
          />
        </label>
        <button className="signup-btn" type="submit">Sign Up</button>
        <div className="signup-login-link">
          Already have an account?{' '}
          <span className="login-link" onClick={() => navigate('/login')}>Login</span>
        </div>
      </form>
    </div>
  );
} 