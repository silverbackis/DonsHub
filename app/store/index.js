import moment from 'moment-timezone'

export default {
  state: () => ({
    baseUrl: null,
    mercureUrl: null,
    currentMatch: null,
    avatars: {
      femaleBluetop: {
        image: 'female-bluetop.png',
        description: 'White female with brown hair and blue sleeveless top'
      },
      maleGlasses: {
        image: 'male-glasses.png',
        description:
          'Black male with gold glasses, goatee beard and a blue top with gold collar'
      },
      femaleHeadBand: {
        image: 'female-yellowheadband.png',
        description:
          'Light skin female with ginger/blond hair, gold headband, headphones and a blue top'
      },
      maleBlond: {
        image: 'male-blond.png',
        description: 'White male with light blond hair and a blue top'
      },
      maleStripes: {
        image: 'male-yellowstripes.png',
        description:
          'White male with blue hair and a blue top with gold vertical stripes'
      },
      femaleAfro: {
        image: 'female-afro.png',
        description: 'Black female with afro, gold top and blue sleeves'
      },
      maleSmart: {
        image: 'male-yellowtie.png',
        description:
          'Light skin male with black hair, gold tie and a collared blue shirt'
      },
      femalePonytail: {
        image: 'female-ponytail.png',
        description:
          'Mixed race female with brown hair in a ponytail and blue top'
      },
      femaleSleeves: {
        image: 'female-yellowsleeves.png',
        description: 'Pale female with black hair, blue top and gold sleeves'
      },
      maleBeard: {
        image: 'male-beard.png',
        description:
          'White male with short brown hair and a beard. Blue top, gold collar'
      },
      maleScarf: {
        image: 'male-scarf.png',
        description:
          'Dark skinned male with short brown hair, a gold scarf and a blue top'
      },
      maleBadge: {
        image: 'male-yellowbadge.png',
        description:
          'Asian male with medium length hair, blue top and a gold badge'
      }
    }
  }),
  getters: {
    matchDateTime(state) {
      return moment.utc(state.currentMatch.matchDateTime)
    },
    gatesDateTime(state) {
      return moment.utc(state.currentMatch.gatesOpen)
    },
    matchDisplayDateTime(state, getters) {
      return getters.matchDateTime
        .tz('Europe/London')
        .format('Do MMM Y @ HH:mm')
    },
    gatesOpen(state, getters) {
      return moment.utc().diff(getters.gatesDateTime) > 0
    }
  },
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
