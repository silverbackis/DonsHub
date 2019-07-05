<template>
  <p v-if="difference">
    <span v-if="difference.days">{{ difference.days }}d&nbsp;</span>
    <span v-if="difference.hours">{{ difference.hours }}h&nbsp;</span>
    {{ difference.minutes }}m&nbsp;{{ difference.seconds }}s
  </p>
</template>

<script>
import moment from 'moment-timezone'

export default {
  props: {
    matchDateTime: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      currentTime: null,
      difference: null,
      interval: null
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
      this.currentTime = moment.tz('Europe/London')
      const ms = this.matchDateTime.diff(this.currentTime, 'milliseconds')
      this.difference = {
        days: moment.duration(ms).days(),
        hours: moment.duration(ms).hours(),
        minutes: this.prefixZero(moment.duration(ms).minutes()),
        seconds: this.prefixZero(moment.duration(ms).seconds())
      }
    }
  }
}
</script>
