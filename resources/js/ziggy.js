const Ziggy = {
    url: 'http://127.0.0.1:8000',
    port: null,
    defaults: {},
    routes: {
        // ...existing routes...
        "stock-requests.store": { uri: "stock-requests", methods: ['POST'] },
        'manager.users.index': { uri: 'manager/users', methods: ['GET', 'HEAD'] },
        'manager.users.store': { uri: 'manager/users', methods: ['POST'] },
        'manager.users.update': { uri: 'manager/users/{user}', methods: ['PATCH'] },
        'manager.users.destroy': { uri: 'manager/users/{user}', methods: ['DELETE'] },
        'manager.users.toggle-status': { uri: 'manager/users/{user}/toggle-status', methods: ['PATCH'] },
    }
};

export { Ziggy };
