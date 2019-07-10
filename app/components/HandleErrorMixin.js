export default {
  methods: {
    getErrorMessages(error) {
      if (!error.response) {
        return [error]
      }
      const data = error.response.data
      if (data.violations !== undefined) {
        return data.violations
      }
      if (data['hydra:description']) {
        return [data['hydra:description']]
      }
      if (data.error && data.error.message) {
        return [data.error.message]
      }
      return [data]
    }
  }
}
