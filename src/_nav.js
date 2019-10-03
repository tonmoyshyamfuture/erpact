export default {
  items: [{
      name: 'Dashboard',
      url: '/',
      icon: 'fa fa-dashboard',
      external: false
    },
    {
      name: 'Contacts',
      url: 'http://localhost/acthtml/dashboard.php',
      icon: 'fa fa-envelope-o',
      external: true
    },
    {
      name: 'Transactions',
      url: '/',
      icon: 'fa fa-list',
      is_tran: true,
      children: [        
        {
          name: 'Sales',
          url: 'sales-add',
          icon: '',
          external: false,
          tran_type: 5
        },
        {
          name: 'Sales List',
          url: '/sales/5',
          icon: '',
          external: false
        },
        {
          name: 'Purchase',
          url: 'purchase-add',
          icon: '',
          external: false,
          tran_type: 6
        },
        {
          name: 'Purchase List',
          url: '/purchase/6',
          icon: '',
          external: false
        },
        {
          name: 'Sales Order',
          url: 'sales-order-add',
          icon: '',
          external: false,
          tran_type: 7
        },
        {
          name: 'Sales Order List',
          url: '/sales-order/7',
          icon: '',
          external: false
        },
        {
          name: 'Purchase Order',
          url: 'purchase-order-add',
          icon: '',
          external: false,
          tran_type: 8
        },
        {
          name: 'Purchase Order List',
          url: '/purchase-order/8',
          icon: '',
          external: false
        },
        {
          name: 'Receive Note',
          url: 'receive-note-add',
          icon: '',
          external: false,
          tran_type: 9
        },
        {
          name: 'Receive Note List',
          url: '/receive-note/9',
          icon: '',
          external: false
        },
        {
          name: 'Delivery Note',
          url: 'delivery-note-add',
          icon: '',
          external: false,
          tran_type: 10
        },
        {
          name: 'Delivery Note List',
          url: '/delivery-note/10',
          icon: '',
          external: false
        },
        {
          name: 'Debit Note',
          url: 'debit-note-add',
          icon: '',
          external: false,
          tran_type: 12
        },
        {
          name: 'Debit Note List',
          url: '/debit-note/12',
          icon: '',
          external: false
        },
        {
          name: 'Credit Note',
          url: 'credit-note-add',
          icon: '',
          external: false,
          tran_type: 14
        },
        {
          name: 'Credit Note List',
          url: '/credit-note/14',
          icon: '',
          external: false
        },
      ]
    },
    {
      name: 'Settings',
      url: '/',
      icon: 'fa fa-gear',
      children: [{
          name: 'Organization',
          url: 'http://localhost/acthtml/dashboard.php',
          icon: '',
          external: true
        },
        {
          name: 'Configuration',
          url: 'http://localhost/acthtml/dashboard.php',
          icon: '',
          external: true
        },
      ]
    },
    {
      name: 'Reports',
      url: '/',
      icon: 'fa fa-bar-chart',
      children: [{
          name: 'Organization',
          url: 'http://localhost/acthtml/dashboard.php',
          icon: '',
          external: true
        },
        {
          name: 'Configuration',
          url: 'http://localhost/acthtml/dashboard.php',
          icon: '',
          external: true
        },
      ]
    },
  ],
};