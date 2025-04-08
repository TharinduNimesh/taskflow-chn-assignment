USE `taskflow_db`;

-- First, insert tags
INSERT INTO tags (name) VALUES 
('frontend'),
('backend'),
('bug'),
('feature'),
('documentation'),
('security'),
('performance'),
('UI/UX'),
('database'),
('api');

-- Insert 10 detailed tasks
INSERT INTO tasks (title, description, category, priority, status) VALUES
(
    'Implement OAuth2 Authentication System',
    '<h3>Authentication System Implementation</h3>
    <ul>
        <li>Implement OAuth2 flow with multiple providers (Google, GitHub, Facebook)</li>
        <li>Set up JWT token generation and validation</li>
        <li>Implement secure password hashing using bcrypt</li>
        <li>Create refresh token mechanism with Redis storage</li>
        <li>Add rate limiting for login attempts</li>
    </ul>
    <p><strong>Tech Stack:</strong> Node.js, Redis, JWT</p>',
    'development',
    'high',
    'in_progress'
),
(
    'Database Performance Optimization',
    '<h3>Database Optimization Tasks</h3>
    <ul>
        <li>Analyze slow query logs and identify bottlenecks</li>
        <li>Implement database indexing strategy</li>
        <li>Set up query caching using Redis</li>
        <li>Optimize JOIN operations in identified slow queries</li>
        <li>Implement database connection pooling</li>
    </ul>
    <p><strong>Target:</strong> 50% reduction in query execution time</p>',
    'development',
    'high',
    'pending'
),
(
    'REST API Documentation Update',
    '<h3>API Documentation Requirements</h3>
    <ul>
        <li>Document all API endpoints using OpenAPI 3.0 specification</li>
        <li>Include authentication methods and examples</li>
        <li>Add request/response examples for each endpoint</li>
        <li>Document error codes and handling</li>
        <li>Create postman collection for testing</li>
    </ul>
    <p><strong>Tools:</strong> Swagger UI, Postman</p>',
    'planning',
    'medium',
    'pending'
),
(
    'E-commerce Cart Microservice',
    '<h3>Shopping Cart Microservice Development</h3>
    <ul>
        <li>Design cart data model and API endpoints</li>
        <li>Implement real-time inventory checking</li>
        <li>Add Redis caching for cart items</li>
        <li>Implement cart expiration mechanism</li>
        <li>Add webhook notifications for inventory updates</li>
    </ul>
    <p><strong>Tech Stack:</strong> Node.js, Redis, MongoDB</p>',
    'development',
    'high',
    'pending'
),
(
    'Mobile Responsive Design Implementation',
    '<h3>Responsive Design Tasks</h3>
    <ul>
        <li>Implement mobile-first approach for main layout</li>
        <li>Create responsive navigation menu</li>
        <li>Optimize images for mobile devices</li>
        <li>Implement lazy loading for images</li>
        <li>Test on various device sizes</li>
    </ul>
    <p><strong>Breakpoints:</strong> 320px, 768px, 1024px, 1440px</p>',
    'development',
    'medium',
    'pending'
),
(
    'Payment Gateway Integration',
    '<h3>Payment System Implementation</h3>
    <ul>
        <li>Integrate Stripe and PayPal payment gateways</li>
        <li>Implement webhook handlers for payment events</li>
        <li>Add payment failure recovery mechanism</li>
        <li>Implement subscription billing system</li>
        <li>Create detailed transaction logs</li>
    </ul>
    <p><strong>Requirements:</strong> PCI Compliance, 3D Secure</p>',
    'development',
    'high',
    'pending'
),
(
    'Automated Testing Suite Setup',
    '<h3>Testing Infrastructure Setup</h3>
    <ul>
        <li>Set up Jest for unit testing</li>
        <li>Implement Cypress for E2E testing</li>
        <li>Create CI/CD pipeline for automated testing</li>
        <li>Set up test coverage reporting</li>
        <li>Create mock data generators</li>
    </ul>
    <p><strong>Coverage Target:</strong> Minimum 80% code coverage</p>',
    'testing',
    'high',
    'pending'
),
(
    'Real-time Chat Feature Implementation',
    '<h3>Chat System Requirements</h3>
    <ul>
        <li>Implement WebSocket connection handling</li>
        <li>Add message persistence in MongoDB</li>
        <li>Implement user presence detection</li>
        <li>Add file sharing capability</li>
        <li>Implement message read receipts</li>
    </ul>
    <p><strong>Tech Stack:</strong> Socket.io, MongoDB, Redis</p>',
    'development',
    'medium',
    'pending'
),
(
    'Security Audit Implementation',
    '<h3>Security Updates Required</h3>
    <ul>
        <li>Implement CSRF protection</li>
        <li>Add XSS prevention measures</li>
        <li>Update dependency versions</li>
        <li>Implement Content Security Policy</li>
        <li>Add SQL injection prevention</li>
    </ul>
    <p><strong>Standards:</strong> OWASP Top 10 compliance</p>',
    'development',
    'high',
    'pending'
),
(
    'Analytics Dashboard Development',
    '<h3>Dashboard Features</h3>
    <ul>
        <li>Create real-time data visualization components</li>
        <li>Implement customizable widgets</li>
        <li>Add PDF report generation</li>
        <li>Implement data export functionality</li>
        <li>Add user activity tracking</li>
    </ul>
    <p><strong>Tech Stack:</strong> React, D3.js, Chart.js</p>',
    'development',
    'medium',
    'pending'
);

-- Link tasks with tags
INSERT INTO task_tags (task_id, tag_id)
SELECT t.id, tg.id
FROM tasks t, tags tg
WHERE 
    (t.title = 'Implement OAuth2 Authentication System' AND tg.name IN ('backend', 'security')) OR
    (t.title = 'Database Performance Optimization' AND tg.name IN ('database', 'performance')) OR
    (t.title = 'REST API Documentation Update' AND tg.name IN ('documentation', 'api')) OR
    (t.title = 'E-commerce Cart Microservice' AND tg.name IN ('backend', 'feature')) OR
    (t.title = 'Mobile Responsive Design Implementation' AND tg.name IN ('frontend', 'UI/UX')) OR
    (t.title = 'Payment Gateway Integration' AND tg.name IN ('backend', 'feature')) OR
    (t.title = 'Automated Testing Suite Setup' AND tg.name IN ('testing')) OR
    (t.title = 'Real-time Chat Feature Implementation' AND tg.name IN ('feature', 'backend')) OR
    (t.title = 'Security Audit Implementation' AND tg.name IN ('security')) OR
    (t.title = 'Analytics Dashboard Development' AND tg.name IN ('frontend', 'feature'));