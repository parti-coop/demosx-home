on_app_servers_and_utilities do
  %w(public/wp-content/uploads public/wp-content/plugins production-config.php).each do |item_path|
      run "ln -nfs #{config.shared_path}/#{item_path} #{config.release_path}/#{item_path}"
  end
end
