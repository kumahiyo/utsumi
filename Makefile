.PHONY: check-ip

# ec-cubeコンテナが外部通信する際の送信元グローバルIPを確認する
# (GMO EpsilonなどのIP制限設定に使う値)
check-ip:
	docker compose exec ec-cube curl -s -4 https://ifconfig.me
	@echo
