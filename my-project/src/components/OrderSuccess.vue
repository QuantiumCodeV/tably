<template>
  <div class="order-success-overlay" v-if="visible">
    <div class="order-success-modal">
      <div class="order-success-header">
        <h2>Заказ успешно оформлен!</h2>
        <button class="close-success-btn" @click="close">×</button>
      </div>
      
      <div class="order-success-content">
        <div class="success-icon">✓</div>
        <p class="success-message">Ваш заказ принят и передан на кухню</p>
        <p class="cooking-time">Примерное время приготовления: 15-20 минут</p>
        
        <div class="payment-info">
          <p>Способ оплаты: <span>{{ getPaymentMethodName(paymentMethod) }}</span></p>
          <p>Статус оплаты: <span>{{ paymentStatus === 'paid' ? 'Оплачено' : 'Ожидает оплаты' }}</span></p>
        </div>
        
        <div class="order-items">
          <h3>Состав заказа:</h3>
          <div class="order-item" v-for="item in orderItems" :key="item.id">
            <div class="order-item-name">{{ item.name }}</div>
            <div class="order-item-quantity">{{ item.quantity }} шт.</div>
            <div class="order-item-price">{{ item.price * item.quantity }} ₽</div>
          </div>
          
          <div class="order-total">
            <div>Итого:</div>
            <div class="total-price">{{ orderTotal }} ₽</div>
          </div>
        </div>
      </div>
      
      <div class="order-success-footer">
        <button class="btn-continue" @click="close">Продолжить</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OrderSuccess',
  props: {
    visible: {
      type: Boolean,
      default: false
    },
    orderId: {
      type: [Number, String],
      default: null
    },
    orderItems: {
      type: Array,
      default: () => []
    },
    orderTotal: {
      type: Number,
      default: 0
    },
    paymentMethod: {
      type: String,
      default: 'card'
    },
    paymentStatus: {
      type: String,
      default: 'pending'
    }
  },
  methods: {
    close() {
      this.$emit('close');
    },
    getPaymentMethodName(method) {
      const methods = {
        'card': 'Банковская карта',
        'cash': 'Наличные',
        'sbp': 'СБП'
      };
      
      return methods[method] || method;
    }
  }
}
</script>

<style scoped>
.order-success-overlay {
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

.order-success-modal {
  background-color: white;
  border-radius: 8px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
}

.order-success-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #eee;
  background-color: #2ecc71;
  color: white;
}

.order-success-header h2 {
  margin: 0;
  font-size: 1.4rem;
}

.close-success-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: white;
}

.order-success-content {
  padding: 1.5rem;
  overflow-y: auto;
}

.success-icon {
  font-size: 3rem;
  color: #2ecc71;
  text-align: center;
  margin-bottom: 1rem;
}

.success-message {
  font-size: 1.2rem;
  text-align: center;
  margin-bottom: 0.5rem;
  font-weight: bold;
}

.cooking-time {
  text-align: center;
  color: #666;
  margin-bottom: 1.5rem;
}

.payment-info {
  background-color: #f9f9f9;
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1.5rem;
}

.payment-info p {
  margin: 0.5rem 0;
}

.payment-info span {
  font-weight: bold;
}

.order-items {
  margin-top: 1.5rem;
}

.order-items h3 {
  margin-top: 0;
  margin-bottom: 1rem;
  font-size: 1.1rem;
}

.order-item {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  border-bottom: 1px solid #eee;
}

.order-item-name {
  flex: 1;
}

.order-item-quantity {
  width: 60px;
  text-align: center;
}

.order-item-price {
  width: 80px;
  text-align: right;
  font-weight: bold;
}

.order-total {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 2px solid #eee;
  font-weight: bold;
}

.total-price {
  font-size: 1.2rem;
  color: #e74c3c;
}

.order-success-footer {
  padding: 1rem;
  border-top: 1px solid #eee;
  display: flex;
  justify-content: center;
}

.btn-continue {
  padding: 0.75rem 2rem;
  background-color: #2ecc71;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  font-size: 1rem;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@media (max-width: 768px) {
  .order-success-modal {
    width: 95%;
    max-height: 80vh;
  }
}
</style> 