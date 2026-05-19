/**
 * @typedef {{ id: number, title: string }} Category
 * @typedef {{ id: number, title: string }} Size
 * @typedef {{ id: number, title: string, code: string }} Color
 */

/**
 * @typedef {Object} Product
 * @property {string} title
 * @property {string|null} image
 * @property {Category} category
 * @property {Size[]} sizes
 * @property {Color[]} colors
 */

/**
 * @typedef {Object} ProductDetail
 * @property {number} id
 * @property {string} title
 * @property {string|null} image
 * @property {string[]} images
 * @property {string} description
 * @property {number} price
 * @property {number} stock
 * @property {number} views
 * @property {string} status
 * @property {Category} category
 * @property {Size[]} sizes
 * @property {Color[]} colors
 */

/**
 * @typedef {Object} OrderItem
 * @property {Object} product
 * @property {number} quantity
 * @property {number} total_amount
 */

/**
 * @typedef {Object} Order
 * @property {string} order_number
 * @property {string} customer_name
 * @property {string} customer_email
 * @property {string} customer_phone_number
 * @property {string} customer_address
 * @property {string} payment_method
 * @property {number} number_of_items
 * @property {number} total_amount
 * @property {string} status
 * @property {OrderItem[]} items
 * @property {string} created_at
 */

/**
 * @typedef {Object} PlaceOrderRequest
 * @property {string} customer_name
 * @property {string} customer_email
 * @property {string} customer_phone_number
 * @property {string} customer_address
 * @property {string} payment_method
 * @property {{ product_id: number, quantity: number, size_id: number, color_id: number }[]} items
 */
