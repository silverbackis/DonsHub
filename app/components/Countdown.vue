<template>
  <div>
    <p>{{ gatesOpen ? 'Match starts' : 'Gates open' }} in</p>
    <p v-if="difference" class="is-size-4 is-size-5-mobile">
      <template v-if="ms <= 0">
        --
      </template>
      <template v-else>
        <span v-if="difference.days">{{ difference.days }}d&nbsp;</span>
        <span v-if="difference.hours">{{ difference.hours }}h&nbsp;</span>
        {{ difference.minutes }}m&nbsp;{{ difference.seconds }}s
      </template>
    </p>
  </div>
</template>

<script>
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
</script>
