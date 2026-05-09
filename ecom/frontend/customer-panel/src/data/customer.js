export const customer = {
  name: 'Usman',
  email: 'usman@example.com'
}

export const stats = {
  totalOrders: 14,
  inTransit: 2,
  delivered: 11,
  saved: 8
}

export const orders = [
  {
    id: 'ORD-1042',
    date: '2026-05-04',
    status: 'shipped',
    total: 134.97,
    items: [
      { name: 'Classic Cotton T-Shirt', image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200' },
      { name: 'Pullover Hoodie', image: 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=200' },
      { name: 'Low-Top Sneakers', image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=200' }
    ]
  },
  {
    id: 'ORD-1038',
    date: '2026-04-29',
    status: 'pending',
    total: 49.99,
    items: [
      { name: 'Slim Fit Oxford Shirt', image: 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=200' }
    ]
  },
  {
    id: 'ORD-1027',
    date: '2026-04-15',
    status: 'delivered',
    total: 188.99,
    items: [
      { name: 'Bomber Jacket', image: 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=200' },
      { name: 'Skinny Fit Jeans', image: 'https://images.unsplash.com/photo-1582552938357-32b906df40cb?w=200' }
    ]
  },
  {
    id: 'ORD-1019',
    date: '2026-04-02',
    status: 'delivered',
    total: 64.99,
    items: [
      { name: 'Zip-Up Hoodie', image: 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=200' }
    ]
  },
  {
    id: 'ORD-1004',
    date: '2026-03-21',
    status: 'cancelled',
    total: 109.00,
    items: [
      { name: 'Running Trainers', image: 'https://images.unsplash.com/photo-1539185441755-769473a23570?w=200' }
    ]
  },
  {
    id: 'ORD-0998',
    date: '2026-03-08',
    status: 'delivered',
    total: 199.00,
    items: [
      { name: 'Wool Peacoat', image: 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=200' }
    ]
  }
]

export const favorites = [
  { id: 1, name: 'Classic Cotton T-Shirt', category: 'T-Shirts', price: 24.99, image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600' },
  { id: 4, name: 'Bomber Jacket', category: 'Jackets', price: 119.00, image: 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600' },
  { id: 5, name: 'Pullover Hoodie', category: 'Hoodies', price: 54.99, image: 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=600' },
  { id: 6, name: 'Low-Top Sneakers', category: 'Shoes', price: 89.99, image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600' },
  { id: 8, name: 'Flannel Shirt', category: 'Shirts', price: 59.99, image: 'https://images.unsplash.com/photo-1608228088998-57828365d486?w=600' },
  { id: 10, name: 'Wool Peacoat', category: 'Jackets', price: 199.00, image: 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600' },
  { id: 11, name: 'Zip-Up Hoodie', category: 'Hoodies', price: 64.99, image: 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=600' },
  { id: 12, name: 'Running Trainers', category: 'Shoes', price: 109.00, image: 'https://images.unsplash.com/photo-1539185441755-769473a23570?w=600' }
]
