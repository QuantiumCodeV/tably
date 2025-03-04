<template>
  <div class="table-view">
    <div v-if="loading" class="loading">
      <div class="spinner"></div>
      <p>Загрузка...</p>
    </div>
    
    <div v-else-if="error" class="error">
      <p>{{ error }}</p>
      <button @click="retryLoading" class="btn">Попробовать снова</button>
    </div>
    
    <template v-else>
      <RestaurantHeader :restaurant="restaurant" />
      
      <div class="container">
        <div class="table-info">
          <h2>Стол №{{ table.number }}</h2>
          <p>Вместимость: {{ table.capacity }} человек</p>
        </div>
        
        <MenuCategories 
          :categories="menuCategories" 
          @add-to-cart="addToCart" 
        />
        
        <CartSidebar 
          :cart="cart" 
          @update-item="updateCartItem"
          @remove-item="removeCartItem"
          @clear-cart="clearCart"
        />
      </div>
    </template>
  </div>
</template>

<script>
import RestaurantHeader from '../components/RestaurantHeader.vue'
import MenuCategories from '../components/MenuCategories.vue'
import CartSidebar from '../components/CartSidebar.vue'

export default {
  name: 'TableView',
  components: {
    RestaurantHeader,
    MenuCategories,
    CartSidebar
  },
  props: {
    id: {
      type: [String, Number],
      required: true
    }
  },
  computed: {
    loading() {
      return this.$store.state.loading
    },
    error() {
      return this.$store.state.error
    },
    table() {
      return this.$store.state.table || {}
    },
    restaurant() {
      return this.$store.state.restaurant || {}
    },
    menuCategories() {
      return this.$store.state.menuCategories || []
    },
    cart() {
      return this.$store.state.cart || { items: [], total: 0 }
    }
  },
  methods: {
    retryLoading() {
      this.loadTableData()
    },
    loadTableData() {
      this.$store.dispatch('loadTableData', this.id)
    },
    addToCart(menuItemId, quantity = 1) {
      this.$store.dispatch('addToCart', { menuItemId, quantity })
    },
    updateCartItem(cartItemId, quantity) {
      this.$store.dispatch('updateCartItem', { cartItemId, quantity })
    },
    removeCartItem(cartItemId) {
      this.$store.dispatch('removeCartItem', cartItemId)
    },
    clearCart() {
      this.$store.dispatch('clearCart')
    }
  },
  created() {
    this.loadTableData()
  },
  watch: {
    id() {
      this.loadTableData()
    }
  }
}
</script>

<style scoped>
.table-view {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.table-info {
  background-color: white;
  padding: 1rem;
  border-radius: 8px;
  margin: 1rem 0;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.spinner {
  border: 4px solid rgba(0, 0, 0, 0.1);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border-left-color: #e74c3c;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style> 