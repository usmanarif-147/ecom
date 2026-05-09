export const categories = ['T-Shirts', 'Shirts', 'Jeans', 'Jackets', 'Hoodies', 'Shoes']

export const products = [
  {
    id: 1,
    name: 'Classic Cotton T-Shirt',
    price: 24.99,
    category: 'T-Shirts',
    image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600',
    sizes: ['S', 'M', 'L', 'XL'],
    colors: ['White', 'Black', 'Navy'],
    description: 'Soft, breathable cotton tee with a relaxed fit. Perfect for everyday wear.',
    stock: 42
  },
  {
    id: 2,
    name: 'Slim Fit Oxford Shirt',
    price: 49.99,
    category: 'Shirts',
    image: 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600',
    sizes: ['S', 'M', 'L', 'XL'],
    colors: ['White', 'Light Blue'],
    description: 'Tailored oxford shirt with a modern slim cut. Wrinkle-resistant cotton blend.',
    stock: 18
  },
  {
    id: 3,
    name: 'Straight Leg Denim',
    price: 69.99,
    category: 'Jeans',
    image: 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600',
    sizes: ['28', '30', '32', '34', '36'],
    colors: ['Indigo', 'Black', 'Stonewash'],
    description: 'Classic 5-pocket straight-leg jeans in mid-weight denim with a touch of stretch.',
    stock: 65
  },
  {
    id: 4,
    name: 'Bomber Jacket',
    price: 119.00,
    category: 'Jackets',
    image: 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600',
    sizes: ['S', 'M', 'L', 'XL'],
    colors: ['Olive', 'Black'],
    description: 'Lightweight bomber jacket with ribbed cuffs and a satin lining.',
    stock: 12
  },
  {
    id: 5,
    name: 'Pullover Hoodie',
    price: 54.99,
    category: 'Hoodies',
    image: 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=600',
    sizes: ['S', 'M', 'L', 'XL', 'XXL'],
    colors: ['Heather Gray', 'Black', 'Burgundy'],
    description: 'Heavyweight fleece hoodie with kangaroo pocket and adjustable drawstring.',
    stock: 33
  },
  {
    id: 6,
    name: 'Low-Top Sneakers',
    price: 89.99,
    category: 'Shoes',
    image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600',
    sizes: ['8', '9', '10', '11', '12'],
    colors: ['White', 'Black'],
    description: 'Minimalist leather sneakers with a cushioned insole and flexible rubber sole.',
    stock: 27
  },
  {
    id: 7,
    name: 'Graphic Print Tee',
    price: 29.99,
    category: 'T-Shirts',
    image: 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=600',
    sizes: ['S', 'M', 'L', 'XL'],
    colors: ['White', 'Black'],
    description: 'Statement graphic tee in soft ringspun cotton with screen-printed artwork.',
    stock: 51
  },
  {
    id: 8,
    name: 'Flannel Shirt',
    price: 59.99,
    category: 'Shirts',
    image: 'https://images.unsplash.com/photo-1608228088998-57828365d486?w=600',
    sizes: ['S', 'M', 'L', 'XL'],
    colors: ['Red Plaid', 'Green Plaid'],
    description: 'Cozy brushed flannel with a regular fit and chest pocket detail.',
    stock: 22
  },
  {
    id: 9,
    name: 'Skinny Fit Jeans',
    price: 64.99,
    category: 'Jeans',
    image: 'https://images.unsplash.com/photo-1582552938357-32b906df40cb?w=600',
    sizes: ['28', '30', '32', '34'],
    colors: ['Black', 'Charcoal'],
    description: 'Modern skinny-fit denim with stretch for all-day comfort.',
    stock: 40
  },
  {
    id: 10,
    name: 'Wool Peacoat',
    price: 199.00,
    category: 'Jackets',
    image: 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600',
    sizes: ['S', 'M', 'L', 'XL'],
    colors: ['Navy', 'Charcoal'],
    description: 'Tailored double-breasted peacoat in a wool blend with notched lapels.',
    stock: 8
  },
  {
    id: 11,
    name: 'Zip-Up Hoodie',
    price: 64.99,
    category: 'Hoodies',
    image: 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=600',
    sizes: ['S', 'M', 'L', 'XL'],
    colors: ['Gray', 'Black', 'Navy'],
    description: 'Full-zip hoodie with side pockets and a soft brushed-fleece interior.',
    stock: 29
  },
  {
    id: 12,
    name: 'Running Trainers',
    price: 109.00,
    category: 'Shoes',
    image: 'https://images.unsplash.com/photo-1539185441755-769473a23570?w=600',
    sizes: ['8', '9', '10', '11', '12'],
    colors: ['White/Gray', 'Black/Red'],
    description: 'Lightweight performance trainers with breathable mesh and responsive cushioning.',
    stock: 19
  }
]

export const cartItems = [
  { productId: 1, quantity: 2, size: 'M', color: 'Black' },
  { productId: 5, quantity: 1, size: 'L', color: 'Heather Gray' },
  { productId: 6, quantity: 1, size: '10', color: 'White' }
]
