<template>
  <div>
    <h1>Login</h1>
    <form @submit.prevent="login">
      <input type="email" v-model="email" placeholder="Email" required />
      <input type="password" v-model="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
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

        // Kontrollo nëse përgjigja është JSON i vlefshëm
        const text = await response.text();
        let data;
        try {
          data = JSON.parse(text);
        } catch (e) {
          throw new Error('Invalid JSON response from server.');
        }

        if (data.status === 'success') {
          // Ruaj ID-në e përdoruesit në localStorage
          localStorage.setItem('userId', data.user.id);
          alert('Login successful');
          // Drejto te faqja e Expenses
          this.$router.push({ name: 'AddExpense' });

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
