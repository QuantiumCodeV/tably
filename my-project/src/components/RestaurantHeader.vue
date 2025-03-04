<template>
  <header class="restaurant-header">
    <div class="container">
      <div class="header-content">
        <div class="logo">
          <img :src="getLogoUrl(restaurant.logo)" :alt="restaurant.name">
        </div>
        <div class="restaurant-info">
          <h1>{{ restaurant.name }}</h1>
          <p v-if="restaurant.description">{{ restaurant.description }}</p>
          <div class="contact-info">
            <p v-if="restaurant.address"><strong>Адрес:</strong> {{ restaurant.address }}</p>
            <p v-if="restaurant.phone"><strong>Телефон:</strong> {{ restaurant.phone }}</p>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'RestaurantHeader',
  props: {
    restaurant: {
      type: Object,
      required: true
    }
  },
  methods: {
    getLogoUrl(logo) {
      return logo || '/img/default-logo.png'
    }
  }
}
</script>

<style scoped>
.restaurant-header {
  background-color: white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 1rem 0;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 2rem;
}

.logo {
  flex-shrink: 0;
}

.logo img {
  width: 100px;
  height: 100px;
  object-fit: contain;
  border-radius: 8px;
}

.restaurant-info {
  flex-grow: 1;
}

.restaurant-info h1 {
  margin-bottom: 0.5rem;
  color: #333;
}

.restaurant-info p {
  color: #666;
  margin-bottom: 0.5rem;
}

.contact-info {
  margin-top: 0.5rem;
  font-size: 0.9rem;
}

@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    text-align: center;
    gap: 1rem;
  }
}
</style> 