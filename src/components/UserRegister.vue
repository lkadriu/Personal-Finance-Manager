<template>
  <div class="register-container">
    <div class="register-form">
      <h1>Register</h1>
      <form @submit.prevent="registerUser">
        <div class="input-group">
          <label for="name">Name:</label>
          <input type="text" id="name" v-model="name" required />
        </div>
        <div class="input-group">
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="email" required @input="clearMessage" />
        </div>
        <div class="input-group">
          <label for="password">Password:</label>
          <input type="password" id="password" v-model="password" required />
        </div>
        <div class="input-group">
          <label for="confirmPassword">Confirm Password:</label>
          <input type="password" id="confirmPassword" v-model="confirmPassword" required />
        </div>
        <button type="submit" class="register-button">Register</button>
      </form>
      <p class="back-to-login">
        <router-link to="/login">Go back to login</router-link>
      </p>
      <p v-if="message" :class="messageClass">{{ message }}</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      confirmPassword: '',
    };
  },
  methods: {
    clearMessage() {
      this.message = '';
      this.messageClass = '';
    },
    async registerUser() {
      if (this.password !== this.confirmPassword) {
        alert('Passwords do not match.');
        return;
      }

      const response = await fetch('http://localhost/backend/src/routes/routes.php?action=register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          name: this.name,
          email: this.email,
          password: this.password,
        }),
      });
      const result = await response.json();

      if (result.status === 'success') {
        localStorage.setItem('userId', result.userId);
        localStorage.setItem('userName', this.name);
        
        this.name = '';
        this.email = '';
        this.password = '';
        this.confirmPassword = '';
        
        alert('User registered successfully!');
      } else {
        alert(result.message || 'Registration failed. Please try again.');
      }
    },
  },
};
</script>



<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background: linear-gradient(135deg, #f5f5f5 0%, #dfe9f3 100%);
}

.register-container {
  width: 100vw;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.register-form {
  width: 100%;
  max-width: 400px;
  padding: 40px;
  background-color: #ffffff;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
  border-radius: 12px;
  text-align: center;
}

h1 {
  font-size: 26px;
  margin-bottom: 24px;
  color: #333333;
  font-weight: bold;
}

.input-group {
  margin-bottom: 20px;
}

input[type="text"],
input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 14px 20px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 6px;
  outline: none;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
  border-color: #3a7afe;
  box-shadow: 0 4px 8px rgba(58, 122, 254, 0.2);
}

.register-button {
  width: 100%;
  padding: 14px 20px;
  font-size: 17px;
  background-color: #3a7afe;
  color: #ffffff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.register-button:hover {
  background-color: #356bd9;
  transform: translateY(-2px);
}

.register-button:active {
  transform: translateY(1px);
}

.message {
  margin-top: 15px;
}

.success-message {
  margin-top: 20px;
  color: #28a745;
}

.error-message {
  margin-top: 20px;
  color: #e74c3c;
}

.back-to-login {
  margin-top: 15px;
  font-size: 14px;
}

.back-to-login a {
  color: #3a7afe;
  text-decoration: none;
}

.back-to-login a:hover {
  text-decoration: underline;
}
</style>
