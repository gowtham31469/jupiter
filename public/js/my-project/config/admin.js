module.exports = ({ env }) => ({
  auth: {
    secret: env('ADMIN_JWT_SECRET', '89a2a37d8a50b48fcae9a5eb2e5d2931'),
  },
});
