<template>
  <div class="section">
    <div class="container">
      <div class="columns is-multiline is-centered">
        <div
          v-for="video in videos"
          :key="video.id"
          class="column is-6 is-video"
        >
          <div>
            <img
              src='data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 16 9" enable-background="new 0 0 16 9" xml:space="preserve" height="9px" width="16px"></svg>'
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
  computed: {
    videos() {
      return this.youtubeData['hydra:member']
    }
  },
  async asyncData({ $axios }) {
    const youtubeData = await $axios.$get('/youtube_videos')
    return {
      youtubeData
    }
  },
  methods: {
    videoSrc(id) {
      return 'https://www.youtube.com/embed/' + id
    }
  }
}
</script>

<style lang="sass">
.is-video
  position: relative
  > div
    position: relative
    display: block
    height: 100%
    .video-placeholder
      width: 100%
      ~ iframe
        position: absolute
        top: 0
        left: 0
        width: 100%
        height: 100%
</style>
