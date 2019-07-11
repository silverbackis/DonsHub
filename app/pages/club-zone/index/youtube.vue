<template>
  <div class="section">
    <div class="container">
      <div class="columns is-multiline is-centered is-mobile">
        <div v-if="!youtubeData" class="column is-narrow">
          <div class="loader is-medium is-primary">
            <span class="is-sr-only">Loading</span>
          </div>
        </div>
        <div
          v-for="video in videos"
          v-else
          :key="video.id"
          class="column is-12-touch is-6-desktop is-video"
        >
          <div class="video-holder">
            <img
              :src="transparentSVG"
              alt="Video placeholder"
              class="video-placeholder"
            />
            <iframe
              :src="videoSrc(video.id)"
              allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
            ></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ZonePrefixMixin from '~/components/ZonePrefixMixin'

export default {
  mixins: [ZonePrefixMixin],
  data() {
    return {
      youtubeData: null,
      transparentSVG:
        'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 16 9" xml:space="preserve" height="9px" width="16px"></svg>'
    }
  },
  computed: {
    videos() {
      return this.youtubeData ? this.youtubeData['hydra:member'] : []
    }
  },
  async mounted() {
    this.youtubeData = await this.$axios.$get('/youtube_videos', {
      withCredentials: false
    })
  },
  methods: {
    videoSrc(id) {
      return 'https://www.youtube.com/embed/' + id
    }
  },
  head: {
    title: 'YouTube'
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.is-video
  position: relative
  .video-holder
    position: relative
    display: block
    height: 100%
    background: $grey-lighter
    .video-placeholder
      width: 100%
      ~ iframe
        position: absolute
        top: 0
        left: 0
        width: 100%
        height: 100%
</style>
