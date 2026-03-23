using Data;
using Microsoft.Extensions.Configuration;
using Microsoft.Extensions.DependencyInjection;
using System;
using System.Windows.Forms;

ApplicationConfiguration.Initialize();

var config = new ConfigurationBuilder()
    .SetBasePath(AppContext.BaseDirectory)
    .AddJsonFile("appsettings.json", optional: false, reloadOnChange: false)
    .Build();

var services = new ServiceCollection();
services.Configure<DatabaseOptions>(config.GetSection(DatabaseOptions.SectionName));
services.AddSingleton<IDbConnectionFactory, NpgsqlConnectionFactory>();
services.AddTransient<IGameRepository, GameRepository>();
var provider = services.BuildServiceProvider();

var repository = provider.GetRequiredService<IGameRepository>();

Application.Run(new GameAppContext(repository));

public sealed class GameAppContext : ApplicationContext
{
    private readonly IGameRepository _repository;
    private bool _restarting;

    public GameAppContext(IGameRepository repository)
    {
        _repository = repository;
        ShowNewGame();
    }

    private void ShowNewGame()
    {
        var size = BoardSizeDialog.Show();
        if (size.rows < 2 || size.cols < 2)
        {
            ExitThread();
            return;
        }

        var form = new BoardForm(size.rows, size.cols, _repository);
        form.FormClosed += (_, __) =>
        {
            if (!_restarting)
            {
                ExitThread();
            }
        };
        form.RequestRestart += async (_, __) =>
        {
            _restarting = true;
            try
            {
                await _repository.SaveGameAsync(
                    form.GetPlateauData(),
                    form.GetMoves(),
                    form.GetLines(),
                    form.GetLinesJ1Count(),
                    form.GetLinesJ2Count());
            }
            finally
            {
                form.Close();
                _restarting = false;
                ShowNewGame();
            }
        };

        form.Show();
    }
}

public static class BoardSizeDialog
{
    public static (int rows, int cols) Show()
    {
        using var dialog = new Form
        {
            Text = "Taille du plateau",
            StartPosition = FormStartPosition.CenterScreen,
            FormBorderStyle = FormBorderStyle.FixedDialog,
            MaximizeBox = false,
            MinimizeBox = false,
            Width = 300,
            Height = 180
        };

        var rowsLabel = new Label { Text = "Hauteur", Left = 12, Top = 18, AutoSize = true };
        var colsLabel = new Label { Text = "Largeur", Left = 12, Top = 52, AutoSize = true };

        var rowsInput = new NumericUpDown
        {
            Left = 100,
            Top = 14,
            Minimum = 2,
            Maximum = 50,
            Value = 9
        };

        var colsInput = new NumericUpDown
        {
            Left = 100,
            Top = 48,
            Minimum = 2,
            Maximum = 50,
            Value = 9
        };

        var okButton = new Button { Text = "OK", Left = 100, Top = 88, Width = 70 };
        var cancelButton = new Button { Text = "Annuler", Left = 180, Top = 88, Width = 80 };

        okButton.DialogResult = DialogResult.OK;
        cancelButton.DialogResult = DialogResult.Cancel;

        dialog.Controls.Add(rowsLabel);
        dialog.Controls.Add(colsLabel);
        dialog.Controls.Add(rowsInput);
        dialog.Controls.Add(colsInput);
        dialog.Controls.Add(okButton);
        dialog.Controls.Add(cancelButton);

        dialog.AcceptButton = okButton;
        dialog.CancelButton = cancelButton;

        var result = dialog.ShowDialog();
        if (result != DialogResult.OK)
        {
            return (0, 0);
        }

        return ((int)rowsInput.Value, (int)colsInput.Value);
    }
}
