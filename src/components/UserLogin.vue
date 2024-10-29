<template>
  <div class="login-container">
    <div class="login-form">
      <h1>Login</h1>
      <form @submit.prevent="login">
        <div class="input-group">
          <input type="email" v-model="email" placeholder="Email" required />
        </div>
        <div class="input-group">
          <input type="password" v-model="password" placeholder="Password" required />
        </div>
        <button type="submit" class="login-button">Login</button>
      </form>
      <p class="signup-link">
        Don't have an account? <router-link to="/register">Sign up</router-link>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserLogin',
  data() {
    return {
      email: '',
      password: '',
    };
  },
  methods: {
    async login() {
      try {
        const response = await fetch('http://localhost/backend/src/routes/routes.php?action=login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            email: this.email,
            password: this.password,
          }),
        });

        const text = await response.text();
        let data;
        try {
          data = JSON.parse(text);
        } catch (e) {
          throw new Error('Invalid JSON response from server.');
        }

        if (data.status === 'success') {
          localStorage.setItem('userId', data.user.id);
          alert('Login successful');
          this.$router.push({ name: 'HomePage' });
        } else {
          alert(data.message || 'Login failed. Please check your credentials.');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while logging in. Please try again.');
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

.login-container {
  width: 100vw;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.login-form {
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

input[type="email"]:focus,
input[type="password"]:focus {
  border-color: #3a7afe;
  box-shadow: 0 4px 8px rgba(58, 122, 254, 0.2);
}

.login-button {
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

.login-button:hover {
  background-color: #356bd9;
  transform: translateY(-2px);
}

.login-button:active {
  transform: translateY(1px);
}
.signup-link {
  margin-top: 15px;
  color: #000000;
  font-size: 14px;
}

.signup-link a {
  color: #3a7afe;
  text-decoration: underline;
}

</style>



