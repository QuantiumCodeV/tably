<template>
  <div class="cart-sidebar" :class="{ 'mobile-open': isMobileCartOpen }">
    <div class="cart-header">
      <h2>Ваш заказ</h2>
      <button v-if="isMobile" class="close-cart-btn" @click="closeMobileCart">×</button>
    </div>
    
    <div v-if="cart.items.length > 0" class="cart-items">
      <div v-for="item in cart.items" :key="item.id" class="cart-item">
        <div class="cart-item-info">
          <h3>{{ item.name || 'Неизвестное блюдо' }}</h3>
          <p class="cart-item-price">{{ item.price || 0 }} ₽ × {{ item.quantity }}</p>
        </div>
        <div class="cart-item-actions">
          <button class="btn-small" @click="decreaseQuantity(item.id)">-</button>
          <span class="quantity">{{ item.quantity }}</span>
          <button class="btn-small" @click="increaseQuantity(item.id)">+</button>
          <button class="btn-remove" @click="removeItem(item.id)">×</button>
        </div>
      </div>
      
      <div class="cart-total">
        <p>Итого:</p>
        <p class="total-price">{{ cartTotal }} ₽</p>
      </div>
      
      <button class="btn-checkout" @click="showPaymentModal">Оформить заказ</button>
    </div>
    
    <div v-else class="empty-cart">
      <p>Ваша корзина пуста</p>
      <p>Добавьте блюда из меню</p>
    </div>
  </div>
  
  <!-- Модальное окно выбора способа оплаты -->
  <div v-if="isPaymentModalVisible" class="payment-modal-overlay">
    <div class="payment-modal">
      <div class="payment-modal-header">
        <h3>Выберите способ оплаты</h3>
        <button class="close-modal-btn" @click="hidePaymentModal">×</button>
      </div>
      
      <div class="payment-methods">
        <div 
          class="payment-method-item" 
          :class="{ 'selected': selectedPaymentMethod === 'card' }"
          @click="selectPaymentMethod('card')"
        >
          <div class="payment-method-icon">💳</div>
          <div class="payment-method-name">Банковская карта</div>
        </div>
        
        <div 
          class="payment-method-item" 
          :class="{ 'selected': selectedPaymentMethod === 'cash' }"
          @click="selectPaymentMethod('cash')"
        >
          <div class="payment-method-icon">💵</div>
          <div class="payment-method-name">Наличные</div>
        </div>
        
        <div 
          class="payment-method-item" 
          :class="{ 'selected': selectedPaymentMethod === 'sbp' }"
          @click="selectPaymentMethod('sbp')"
        >
          <div class="payment-method-icon">📱</div>
          <div class="payment-method-name">СБП</div>
        </div>
      </div>
      
      <div class="payment-modal-footer">
        <button 
          class="btn-confirm-payment" 
          :disabled="!selectedPaymentMethod"
          @click="confirmPayment"
        >
          Подтвердить
        </button>
      </div>
    </div>
  </div>
  
  <!-- Компонент успешного оформления заказа -->
  <OrderSuccess
    :visible="isOrderSuccessVisible"
    :order-id="lastOrderId"
    :order-items="cart.items"
    :order-total="cartTotal"
    :payment-method="selectedPaymentMethod"
    :payment-status="'paid'"
    @close="hideOrderSuccess"
  />
  
  <!-- Мобильная иконка корзины -->
  <div v-if="isMobile" class="mobile-cart-icon" @click="openMobileCart">
    <div class="cart-icon">🛒</div>
    <div v-if="cartItemCount > 0" class="cart-badge">{{ cartItemCount }}</div>
  </div>
  
  <!-- Затемнение фона при открытой мобильной корзине -->
  <div 
    v-if="isMobile && isMobileCartOpen" 
    class="mobile-cart-overlay"
    @click="closeMobileCart"
  ></div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import OrderSuccess from './OrderSuccess.vue';

export default {
  name: 'CartSidebar',
  components: {
    OrderSuccess
  },
  data() {
    return {
      isMobile: false,
      isMobileCartOpen: false,
      isPaymentModalVisible: false,
      isOrderSuccessVisible: false,
      selectedPaymentMethod: null,
      lastOrderId: null
    }
  },
  computed: {
    ...mapState(['cart']),
    ...mapGetters(['cartTotal', 'cartItemCount'])
  },
  methods: {
    ...mapActions(['updateCartItem', 'removeFromCart', 'clearCart', 'checkout']),
    
    increaseQuantity(itemId) {
      const item = this.cart.items.find(item => item.id === itemId);
      if (item) {
        this.updateCartItem({ itemId, quantity: item.quantity + 1 });
      }
    },
    
    decreaseQuantity(itemId) {
      const item = this.cart.items.find(item => item.id === itemId);
      if (item) {
        if (item.quantity > 1) {
          this.updateCartItem({ itemId, quantity: item.quantity - 1 });
        } else {
          this.removeItem(itemId);
        }
      }
    },
    
    removeItem(itemId) {
      this.removeFromCart(itemId);
    },
    
    // Методы для модального окна оплаты
    showPaymentModal() {
      this.isPaymentModalVisible = true;
      this.selectedPaymentMethod = null;
      document.body.style.overflow = 'hidden'; // Блокируем прокрутку страницы
    },
    
    hidePaymentModal() {
      this.isPaymentModalVisible = false;
      document.body.style.overflow = ''; // Разблокируем прокрутку страницы
    },
    
    selectPaymentMethod(method) {
      this.selectedPaymentMethod = method;
    },
    
    async confirmPayment() {
      if (!this.selectedPaymentMethod) return;
      
      try {
        // Для тестирования, если корзина пуста
        if (this.cart.items.length === 0) {
          console.log('Тестовый режим: симулируем успешный заказ');
          this.lastOrderId = 'test-' + Date.now();
          this.hidePaymentModal();
          
          setTimeout(() => {
            this.showOrderSuccess();
          }, 100);
          
          if (this.isMobile) {
            this.closeMobileCart();
          }
          
          return;
        }
        
        // Передаем способ оплаты в метод checkout
        const result = await this.checkout({ 
          paymentMethod: this.selectedPaymentMethod 
        });
        
        console.log('Результат checkout:', result); // Добавляем отладочную информацию
        
        if (result && result.success) {
          this.lastOrderId = result.orderId;
          this.hidePaymentModal();
          
          // Добавляем задержку перед показом окна успешного заказа
          setTimeout(() => {
            this.showOrderSuccess();
          }, 100);
          
          if (this.isMobile) {
            this.closeMobileCart();
          }
        } else {
          alert((result && result.error) || 'Ошибка при оформлении заказа');
          this.hidePaymentModal();
        }
      } catch (error) {
        console.error('Ошибка при оформлении заказа:', error);
        alert('Произошла ошибка при оформлении заказа');
        this.hidePaymentModal();
      }
    },
    
    // Методы для окна успешного оформления заказа
    showOrderSuccess() {
      console.log('Показываем окно успешного заказа'); // Добавляем отладочную информацию
      this.isOrderSuccessVisible = true;
      document.body.style.overflow = 'hidden'; // Блокируем прокрутку страницы
    },
    
    hideOrderSuccess() {
      console.log('Скрываем окно успешного заказа'); // Добавляем отладочную информацию
      this.isOrderSuccessVisible = false;
      document.body.style.overflow = ''; // Разблокируем прокрутку страницы
    },
    
    // Методы для мобильной корзины
    openMobileCart() {
      this.isMobileCartOpen = true;
      document.body.style.overflow = 'hidden'; // Блокируем прокрутку страницы
    },
    
    closeMobileCart() {
      this.isMobileCartOpen = false;
      document.body.style.overflow = ''; // Разблокируем прокрутку страницы
    },
    
    checkScreenSize() {
      this.isMobile = window.innerWidth < 768;
    }
  },
  mounted() {
    // Проверяем размер экрана при монтировании
    this.checkScreenSize();
    
    // Добавляем обработчик изменения размера окна
    window.addEventListener('resize', this.checkScreenSize);
  },
  beforeUnmount() {
    // Удаляем обработчик при размонтировании компонента
    window.removeEventListener('resize', this.checkScreenSize);
    
    // Убираем блокировку прокрутки, если она была добавлена
    if (this.isMobileCartOpen || this.isPaymentModalVisible || this.isOrderSuccessVisible) {
      document.body.style.overflow = '';
    }
  }
}
</script>

<style scoped>
.cart-sidebar {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  padding: 1rem;
  height: calc(100vh - 2rem);
  overflow-y: auto;
}

.cart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #eee;
}

.cart-header h2 {
  margin: 0;
  font-size: 1.5rem;
}

.close-cart-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #666;
}

.cart-items {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.cart-item {
  display: flex;
  flex-direction: column;
  padding: 0.75rem;
  border-radius: 8px;
  background-color: #f9f9f9;
}

.cart-item-info {
  margin-bottom: 0.5rem;
}

.cart-item-info h3 {
  margin: 0 0 0.25rem 0;
  font-size: 1rem;
}

.cart-item-price {
  margin: 0;
  color: #666;
}

.cart-item-actions {
  display: flex;
  align-items: center;
}

.btn-small {
  width: 24px;
  height: 24px;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f0f0f0;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
}

.btn-remove {
  width: 24px;
  height: 24px;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #ffeeee;
  border: 1px solid #ffcccc;
  border-radius: 4px;
  color: #e74c3c;
  cursor: pointer;
  margin-left: 0.5rem;
}

.quantity {
  font-weight: bold;
  min-width: 20px;
  text-align: center;
}

.cart-total {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 2px solid #eee;
  font-weight: bold;
}

.total-price {
  color: #e74c3c;
  font-size: 1.2rem;
}

.btn-checkout {
  width: 100%;
  padding: 0.75rem;
  background-color: #e74c3c;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  margin-top: 1rem;
}

.empty-cart {
  text-align: center;
  color: #666;
  padding: 2rem 0;
}

/* Стили для мобильной версии */
.mobile-cart-icon {
  position: fixed;
  bottom: 1.5rem;
  right: 1.5rem;
  width: 60px;
  height: 60px;
  background-color: #e74c3c;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  cursor: pointer;
  z-index: 99;
  transition: transform 0.3s, box-shadow 0.3s;
}

.mobile-cart-icon:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
}

.cart-icon {
  font-size: 1.8rem;
  color: white;
}

.cart-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background-color: #2ecc71;
  color: white;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 0.8rem;
  font-weight: bold;
}

.mobile-cart-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 98;
  animation: fadeIn 0.3s ease;
}

/* Стили для модального окна оплаты */
.payment-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  animation: fadeIn 0.3s ease;
}

.payment-modal {
  background-color: white;
  border-radius: 8px;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
  overflow: hidden;
}

.payment-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #eee;
}

.payment-modal-header h3 {
  margin: 0;
  font-size: 1.2rem;
}

.close-modal-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #666;
}

.payment-methods {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.payment-method-item {
  display: flex;
  align-items: center;
  padding: 0.75rem;
  border: 2px solid #eee;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.payment-method-item:hover {
  background-color: #f9f9f9;
}

.payment-method-item.selected {
  border-color: #e74c3c;
  background-color: #fff5f5;
}

.payment-method-icon {
  font-size: 1.5rem;
  margin-right: 1rem;
}

.payment-method-name {
  font-weight: bold;
}

.payment-modal-footer {
  padding: 1rem;
  border-top: 1px solid #eee;
  display: flex;
  justify-content: flex-end;
}

.btn-confirm-payment {
  padding: 0.75rem 1.5rem;
  background-color: #e74c3c;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
}

.btn-confirm-payment:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

/* Медиа-запросы для адаптивности */
@media (max-width: 768px) {
  .cart-sidebar {
    position: fixed;
    top: auto;
    bottom: 0;
    left: 0;
    right: 0;
    width: 100%;
    max-height: 80vh;
    border-radius: 20px 20px 0 0;
    transform: translateY(100%);
    transition: transform 0.3s ease;
    z-index: 99;
    padding-bottom: 2rem;
    overflow-y: auto;
  }
  
  .cart-sidebar.mobile-open {
    transform: translateY(0);
  }
  
  .cart-items {
    max-height: 50vh;
  }
  
  .payment-modal {
    width: 95%;
    max-width: none;
    margin: 0 10px;
  }
}
</style>