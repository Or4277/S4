using Data;
using System;
using System.Collections.Generic;
using System.Drawing;
using System.Linq;
using System.Windows.Forms;

public sealed class BoardForm : Form
{
    private const int PaddingSize = 20;
    private const float DotRadius = 2.5f;
    private const float PlacedRadius = 7f;
    private const float HitRadius = 10f;

    private readonly int _rows;
    private readonly int _cols;
    private readonly BoardPanel _board;
    private readonly Button _endButton;
    private readonly Label _turnLabel;
    private readonly Label _leftScoreLabel;
    private readonly Label _rightScoreLabel;
    private readonly List<GameMove> _moves = new();
    private readonly List<GameLine> _lines = new();
    private readonly HashSet<string> _lineKeys = new();
    private readonly HashSet<string> _occupied = new();
    private readonly HashSet<string> _playerOne = new();
    private readonly HashSet<string> _playerTwo = new();
    private bool _isPlayerOne = true;

    public event EventHandler? RequestRestart;

    public BoardForm(int rows, int cols, IGameRepository repository)
    {
        _rows = rows;
        _cols = cols;

        Text = $"Plateau {cols}x{rows}";
        Width = 900;
        Height = 750;
        StartPosition = FormStartPosition.CenterScreen;

        _turnLabel = new Label { Text = GetTurnText(), AutoSize = true };
        _leftScoreLabel = new Label { Text = GetLeftScoreText(), AutoSize = true };
        _rightScoreLabel = new Label { Text = GetRightScoreText(), AutoSize = true, TextAlign = ContentAlignment.TopRight };

        _board = new BoardPanel(DrawBoard, HandleBoardClick) { Dock = DockStyle.Fill };

        _endButton = new Button { Text = "Terminer partie", AutoSize = true };
        _endButton.Click += (_, __) => RequestRestart?.Invoke(this, EventArgs.Empty);

        var boardRow = new TableLayoutPanel
        {
            Dock = DockStyle.Fill,
            ColumnCount = 3,
            RowCount = 1
        };
        boardRow.ColumnStyles.Add(new ColumnStyle(SizeType.Absolute, 160));
        boardRow.ColumnStyles.Add(new ColumnStyle(SizeType.Percent, 100));
        boardRow.ColumnStyles.Add(new ColumnStyle(SizeType.Absolute, 160));
        boardRow.Controls.Add(_leftScoreLabel, 0, 0);
        boardRow.Controls.Add(_board, 1, 0);
        boardRow.Controls.Add(_rightScoreLabel, 2, 0);

        var root = new TableLayoutPanel
        {
            Dock = DockStyle.Fill,
            RowCount = 3
        };
        root.RowStyles.Add(new RowStyle(SizeType.AutoSize));
        root.RowStyles.Add(new RowStyle(SizeType.Percent, 100));
        root.RowStyles.Add(new RowStyle(SizeType.AutoSize));
        root.Controls.Add(_turnLabel, 0, 0);
        root.Controls.Add(boardRow, 0, 1);
        root.Controls.Add(_endButton, 0, 2);

        Controls.Add(root);
    }

    public string GetPlateauData()
    {
        var points = string.Join("|", _moves.Select(m => $"{m.X},{m.Y}"));
        return $"rows={_rows};cols={_cols};points={points}";
    }

    public IReadOnlyList<GameMove> GetMoves() => _moves.AsReadOnly();

    public IReadOnlyList<GameLine> GetLines() => _lines.AsReadOnly();

    public int GetLinesJ1Count() => _lines.Count(l => l.Joueur == "J1");

    public int GetLinesJ2Count() => _lines.Count(l => l.Joueur == "J2");

    private void HandleBoardClick(PointF location)
    {
        if (!TryGetBoardGeometry(out var startX, out var startY, out var cellX, out var cellY))
        {
            return;
        }

        var col = (int)Math.Round((location.X - startX) / cellX);
        var row = (int)Math.Round((location.Y - startY) / cellY);

        if (row < 0 || row >= _rows || col < 0 || col >= _cols)
        {
            return;
        }

        var pointX = startX + col * cellX;
        var pointY = startY + row * cellY;
        var dx = location.X - pointX;
        var dy = location.Y - pointY;
        if (Math.Sqrt(dx * dx + dy * dy) > HitRadius)
        {
            return;
        }

        var key = $"{col},{row}";
        if (_occupied.Contains(key))
        {
            return;
        }

        var joueur = _isPlayerOne ? "J1" : "J2";
        var tour = _moves.Count + 1;
        _moves.Add(new GameMove(col, row, joueur, tour));
        _occupied.Add(key);
        if (_isPlayerOne)
        {
            _playerOne.Add(key);
        }
        else
        {
            _playerTwo.Add(key);
        }

        DetectNewLines(col, row, joueur);

        _isPlayerOne = !_isPlayerOne;
        _turnLabel.Text = GetTurnText();
        _leftScoreLabel.Text = GetLeftScoreText();
        _rightScoreLabel.Text = GetRightScoreText();
        _board.Invalidate();
    }

    private void DrawBoard(Graphics g, Rectangle bounds)
    {
        g.Clear(Color.White);
        g.SmoothingMode = System.Drawing.Drawing2D.SmoothingMode.AntiAlias;

        if (!TryGetBoardGeometry(out var startX, out var startY, out var cellX, out var cellY))
        {
            return;
        }

        var boardWidth = cellX * (_cols - 1f);
        var boardHeight = cellY * (_rows - 1f);

        using var gridPen = new Pen(Color.FromArgb(38, 38, 38), 1f);

        for (var r = 0; r < _rows; r++)
        {
            var y = startY + r * cellY;
            g.DrawLine(gridPen, startX, y, startX + boardWidth, y);
        }

        for (var c = 0; c < _cols; c++)
        {
            var x = startX + c * cellX;
            g.DrawLine(gridPen, x, startY, x, startY + boardHeight);
        }

        using var dotBrush = new SolidBrush(Color.FromArgb(25, 25, 25));
        for (var r = 0; r < _rows; r++)
        {
            for (var c = 0; c < _cols; c++)
            {
                var x = startX + c * cellX;
                var y = startY + r * cellY;
                g.FillEllipse(dotBrush, x - DotRadius, y - DotRadius, DotRadius * 2, DotRadius * 2);
            }
        }

        foreach (var move in _moves)
        {
            var x = startX + move.X * cellX;
            var y = startY + move.Y * cellY;
            var color = move.Joueur == "J1" ? Color.FromArgb(217, 38, 38) : Color.FromArgb(38, 89, 217);
            using var brush = new SolidBrush(color);
            g.FillEllipse(brush, x - PlacedRadius, y - PlacedRadius, PlacedRadius * 2, PlacedRadius * 2);
        }

        using var linePen = new Pen(Color.Black, 3f);
        foreach (var line in _lines)
        {
            var points = ParsePoints(line.Points);
            if (points.Count < 2)
            {
                continue;
            }

            linePen.Color = line.Joueur == "J1" ? Color.FromArgb(217, 38, 38) : Color.FromArgb(38, 89, 217);
            var first = points[0];
            var last = points[points.Count - 1];
            g.DrawLine(
                linePen,
                startX + first.x * cellX,
                startY + first.y * cellY,
                startX + last.x * cellX,
                startY + last.y * cellY);
        }
    }

    private void DetectNewLines(int col, int row, string joueur)
    {
        var playerSet = joueur == "J1" ? _playerOne : _playerTwo;
        var directions = new (int dx, int dy)[]
        {
            (1, 0),
            (0, 1),
            (1, 1),
            (1, -1)
        };

        foreach (var (dx, dy) in directions)
        {
            var linePoints = GetLinePoints(col, row, dx, dy, playerSet);
            if (linePoints.Count != 5)
            {
                continue;
            }

            var key = string.Join("|", linePoints.Select(p => $"{p.x},{p.y}")
                .OrderBy(value => value, StringComparer.Ordinal));
            if (_lineKeys.Contains(key))
            {
                continue;
            }

            if (IsBlockedByOpponentLine(linePoints, joueur))
            {
                continue;
            }

            _lineKeys.Add(key);
            var pointsText = string.Join("|", linePoints.Select(p => $"{p.x},{p.y}"));
            _lines.Add(new GameLine(joueur, pointsText));
        }
    }

    private bool IsBlockedByOpponentLine(List<(int x, int y)> linePoints, string joueur)
    {
        if (linePoints.Count < 2)
        {
            return false;
        }

        var newStart = linePoints[0];
        var newEnd = linePoints[^1];

        foreach (var line in _lines)
        {
            if (line.Joueur == joueur)
            {
                continue;
            }

            var points = ParsePoints(line.Points);
            if (points.Count < 2)
            {
                continue;
            }

            var start = points[0];
            var end = points[^1];
            if (SegmentsProperlyIntersect(newStart, newEnd, start, end))
            {
                return true;
            }
        }

        return false;
    }

    private List<(int x, int y)> GetLinePoints(
        int col,
        int row,
        int dx,
        int dy,
        HashSet<string> playerSet)
    {
        var points = new List<(int x, int y)> { (col, row) };

        var x = col + dx;
        var y = row + dy;
        while (IsOwned(x, y, playerSet))
        {
            points.Add((x, y));
            x += dx;
            y += dy;
        }

        x = col - dx;
        y = row - dy;
        while (IsOwned(x, y, playerSet))
        {
            points.Insert(0, (x, y));
            x -= dx;
            y -= dy;
        }

        return points;
    }

    private bool IsOwned(int col, int row, HashSet<string> playerSet)
    {
        if (col < 0 || col >= _cols || row < 0 || row >= _rows)
        {
            return false;
        }

        return playerSet.Contains($"{col},{row}");
    }

    private bool TryGetBoardGeometry(out float startX, out float startY, out float cellX, out float cellY)
    {
        var width = _board.Width;
        var height = _board.Height;
        var usableSize = Math.Min(width, height) - PaddingSize * 2f;

        if (usableSize <= 0 || _rows < 2 || _cols < 2)
        {
            startX = 0;
            startY = 0;
            cellX = 0;
            cellY = 0;
            return false;
        }

        cellX = usableSize / (_cols - 1f);
        cellY = usableSize / (_rows - 1f);

        var boardWidth = cellX * (_cols - 1f);
        var boardHeight = cellY * (_rows - 1f);

        startX = (width - boardWidth) / 2f;
        startY = (height - boardHeight) / 2f;

        return true;
    }

    private string GetTurnText()
    {
        return _isPlayerOne ? "Tour: J1 (rouge)" : "Tour: J2 (bleu)";
    }

    private string GetLeftScoreText()
    {
        return $"J1 (rouge)\nLignes: {GetLinesJ1Count()}";
    }

    private string GetRightScoreText()
    {
        return $"J2 (bleu)\nLignes: {GetLinesJ2Count()}";
    }

    private static List<(int x, int y)> ParsePoints(string points)
    {
        var result = new List<(int x, int y)>();
        if (string.IsNullOrWhiteSpace(points))
        {
            return result;
        }

        var parts = points.Split('|', StringSplitOptions.RemoveEmptyEntries);
        foreach (var part in parts)
        {
            var pair = part.Split(',');
            if (pair.Length != 2)
            {
                continue;
            }

            if (int.TryParse(pair[0], out var x) && int.TryParse(pair[1], out var y))
            {
                result.Add((x, y));
            }
        }

        return result;
    }

    private static bool SegmentsProperlyIntersect(
        (int x, int y) a,
        (int x, int y) b,
        (int x, int y) c,
        (int x, int y) d)
    {
        var o1 = Orientation(a, b, c);
        var o2 = Orientation(a, b, d);
        var o3 = Orientation(c, d, a);
        var o4 = Orientation(c, d, b);

        return o1 != o2 && o3 != o4;
    }

    private static int Orientation((int x, int y) a, (int x, int y) b, (int x, int y) c)
    {
        var value = (b.y - a.y) * (c.x - b.x) - (b.x - a.x) * (c.y - b.y);
        if (value == 0) return 0;
        return value > 0 ? 1 : 2;
    }
}

public sealed class BoardPanel : Panel
{
    private readonly Action<Graphics, Rectangle> _paint;
    private readonly Action<PointF> _click;

    public BoardPanel(Action<Graphics, Rectangle> paint, Action<PointF> click)
    {
        _paint = paint;
        _click = click;
        DoubleBuffered = true;
        BackColor = Color.White;
        Dock = DockStyle.Fill;
    }

    protected override void OnPaint(PaintEventArgs e)
    {
        base.OnPaint(e);
        _paint(e.Graphics, ClientRectangle);
    }

    protected override void OnMouseClick(MouseEventArgs e)
    {
        base.OnMouseClick(e);
        if (e.Button == MouseButtons.Left)
        {
            _click(new PointF(e.X, e.Y));
        }
    }
}
