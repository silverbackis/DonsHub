export default {
  state: () => ({
    baseUrl: null,
    mercureUrl: null,
    currentMatch: null
  }),
  mutations: {
    setBaseUrl(state, baseUrl) {
      state.baseUrl = baseUrl
    },
    setMercureUrl(state, mercureUrl) {
      state.mercureUrl = mercureUrl
    },
    setCurrentMatch(state, currentMatch) {
      state.currentMatch = currentMatch
    }
  },
  actions: {
    async nuxtServerInit({ commit }, { env, $axios }) {
      commit('setBaseUrl', env.baseUrl)
      commit('setMercureUrl', env.mercureUrl)
      try {
        const { data: currentMatch } = await $axios.get('/matches/current')
        commit('setCurrentMatch', currentMatch)
      } catch (err) {}
    }
  }
}
