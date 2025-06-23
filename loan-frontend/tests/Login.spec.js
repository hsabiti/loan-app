import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import Login from '../src/views/Login.vue'

describe('Login.vue', () => {
  it('renders the login form', () => {
    const wrapper = mount(Login)
    expect(wrapper.text()).toContain('Login')
  })

  it('contains email and password inputs', () => {
    const wrapper = mount(Login)
    const email = wrapper.find('input[type="email"]')
    const password = wrapper.find('input[type="password"]')

    expect(email.exists()).toBe(true)
    expect(password.exists()).toBe(true)
  })
})

