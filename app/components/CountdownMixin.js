import { mapGetters } from 'vuex'
import moment from 'moment'

export default {
  data() {
    return {
      currentTime: null,
      difference: null,
      interval: null,
      ms: null
    }
  },
  computed: {
    ...mapGetters({
      match: 'currentMatch',
      gatesOpen: 'gatesOpen',
      matchDateTime: 'matchDateTime',
      gatesDateTime: 'gatesDateTime'
    }),
    gatesTimeDifference() {
      return this.gatesDateTime.diff(this.currentTime, 'milliseconds')
    },
    matchTimeDifference() {
      return this.matchDateTime.diff(this.currentTime, 'milliseconds')
    }
  },
  mounted() {
    this.updateDifference()
    this.interval = setInterval(this.updateDifference, 500)
  },
  beforeDestroy() {
    clearInterval(this.interval)
  },
  methods: {
    prefixZero(number) {
      if (number < 10) {
        return '0' + number
      }
      return number
    },
    updateDifference() {
      this.currentTime = moment.utc()
      this.ms = this.gatesOpen
        ? this.matchTimeDifference
        : this.gatesTimeDifference
      if (this.ms < 0) {
        this.difference = {
          days: 0,
          hours: 0,
          minutes: 0,
          seconds: 0
        }
      } else {
        this.difference = {
          days: moment.duration(this.ms).days(),
          hours: moment.duration(this.ms).hours(),
          minutes: this.prefixZero(moment.duration(this.ms).minutes()),
          seconds: this.prefixZero(moment.duration(this.ms).seconds())
        }
      }
    }
  }
}
